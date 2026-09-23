#!/usr/bin/env bash
set -Eeuo pipefail

cd "$(dirname "$0")/.."
env_file=${1:-.env}
[[ -f "$env_file" ]] || { echo 'Stage environment file is missing' >&2; exit 66; }
# The file may arrive from Windows with CRLF; Bash does not strip the CR.
# shellcheck disable=SC1090
source <(sed 's/\r$//' "$env_file")

case "${PUBLIC_URL:-}" in
  http://localhost:18080|http://127.0.0.1:18080) ;;
  *) echo 'Stage URL must be loopback HTTP on port 18080' >&2; exit 65 ;;
esac
: "${WP_ADMIN_USER:?Set WP_ADMIN_USER}"
: "${WP_ADMIN_PASSWORD:?Set WP_ADMIN_PASSWORD}"
: "${WP_ADMIN_EMAIL:?Set WP_ADMIN_EMAIL}"

compose=(docker compose --env-file "$env_file" -f deploy-next/compose.stage.yaml)
wp() { "${compose[@]}" exec -T wordpress wp --allow-root "$@"; }

# A failed `wp core is-installed` also means "database unavailable". Wait for
# MariaDB before deciding this is an empty installation.
"${compose[@]}" up -d --wait --no-build db >/dev/null

ready=0
for _ in $(seq 1 60); do
  if wp core version >/dev/null 2>&1; then ready=1; break; fi
  sleep 2
done
(( ready == 1 )) || { echo 'WordPress files did not become ready' >&2; exit 1; }

pending_marker=.stage-admin-pending
if ! wp core is-installed >/dev/null 2>&1; then
  # Persist intent before core install. If install succeeds but this script is
  # interrupted, only this release resumes setting its new admin password.
  touch "$pending_marker"
  chmod 600 "$pending_marker"
  # WP-CLI generates a temporary password here. Suppress its output so it
  # cannot appear in deployment logs; the next step sets our secret via stdin.
  if ! wp core install --url="$PUBLIC_URL" --title='TiO2 Products Stage' \
    --admin_user="$WP_ADMIN_USER" --admin_email="$WP_ADMIN_EMAIL" \
    --skip-email --quiet >/dev/null 2>&1; then
    echo 'WordPress core installation failed' >&2
    exit 1
  fi
  # These are created by a fresh WordPress install, before any editor can edit them.
  if [[ "$(wp post get 1 --field=post_name)" == hello-world ]]; then wp post delete 1 --force --quiet; fi
  if [[ "$(wp post get 2 --field=post_name)" == sample-page ]]; then wp post delete 2 --force --quiet; fi
fi

# Existing sites have no local pending marker, so their admin credentials are
# never reset simply because a new bootstrap version is deployed.
if [[ -f "$pending_marker" ]]; then
  if ! wp option get tio2_stage_admin_ready >/dev/null 2>&1; then
    printf '%s\n' "$WP_ADMIN_PASSWORD" | wp eval-file /workspace/deploy-next/set-admin-password.php "$WP_ADMIN_USER"
    wp option add tio2_stage_admin_ready 1 --quiet
  fi
  rm -f -- "$pending_marker"
fi

wp plugin activate tio2-products
wp theme activate tio2
wp option update permalink_structure '/%postname%/' --quiet
wp option update blog_public 0 --quiet
wp option update default_comment_status closed --quiet
wp option update default_ping_status closed --quiet

for importer in import-product.php import-batch.php import-pages.php \
  import-process-applications.php import-resources.php import-documents.php \
  import-markets.php relocate-core-privacy-draft.php import-utility-pages.php; do
  wp eval-file "/workspace/scripts/$importer"
done
wp rewrite flush --hard --quiet
echo 'Stage bootstrap complete.'

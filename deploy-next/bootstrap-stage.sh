#!/usr/bin/env bash
set -Eeuo pipefail

cd "$(dirname "$0")/.."
env_file=${1:-.env}
[[ -f "$env_file" ]] || { echo 'Stage environment file is missing' >&2; exit 66; }
set -a
# The file may arrive from Windows with CRLF; Bash does not strip the CR.
# shellcheck disable=SC1090
source <(sed 's/\r$//' "$env_file")
set +a

case "${PUBLIC_URL:-}" in
  http://localhost:*|http://127.0.0.1:*) ;;
  *) echo 'Stage URL must use loopback HTTP' >&2; exit 65 ;;
esac
: "${WP_ADMIN_USER:?Set WP_ADMIN_USER}"
: "${WP_ADMIN_PASSWORD:?Set WP_ADMIN_PASSWORD}"
: "${WP_ADMIN_EMAIL:?Set WP_ADMIN_EMAIL}"

compose=(docker compose --env-file "$env_file" -f deploy-next/compose.stage.yaml)
wp() { "${compose[@]}" exec -T wordpress wp --allow-root "$@"; }

ready=0
for _ in $(seq 1 60); do
  if wp core version >/dev/null 2>&1; then ready=1; break; fi
  sleep 2
done
(( ready == 1 )) || { echo 'WordPress files did not become ready' >&2; exit 1; }

if ! wp core is-installed >/dev/null 2>&1; then
  wp core install --url="$PUBLIC_URL" --title='TiO2 Products Stage' \
    --admin_user="$WP_ADMIN_USER" --admin_password="$WP_ADMIN_PASSWORD" \
    --admin_email="$WP_ADMIN_EMAIL" --skip-email
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

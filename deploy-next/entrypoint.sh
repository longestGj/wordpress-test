#!/bin/sh
set -eu

sync_site_code() {
  source=$1
  target=$2
  staging="${target}.next.$$"

  mkdir -p "$(dirname "$target")"
  rm -rf -- "$staging"
  cp -a -- "$source" "$staging"
  chown -R www-data:www-data "$staging"
  rm -rf -- "$target"
  mv -- "$staging" "$target"
}

# The upstream image keeps WordPress core in an anonymous /var/www/html volume.
# A new image must receive a fresh anonymous volume, or the old core survives.
if [ -f /var/www/html/wp-includes/version.php ] &&
   ! cmp -s /var/www/html/wp-includes/version.php /usr/src/wordpress/wp-includes/version.php; then
  echo 'WordPress core differs from this image; recreate wordpress with --renew-anon-volumes' >&2
  exit 1
fi

# Refresh code owned by this project; leave named uploads and database alone.
sync_site_code /usr/src/wordpress/wp-content/themes/tio2 /var/www/html/wp-content/themes/tio2
sync_site_code /usr/src/wordpress/wp-content/plugins/tio2-products /var/www/html/wp-content/plugins/tio2-products

exec /usr/local/bin/docker-entrypoint.sh "$@"

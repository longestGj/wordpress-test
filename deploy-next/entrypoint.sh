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

# The WordPress image persists /var/www/html. Refresh only code owned by this
# project when a new image starts; leave uploads and WordPress content alone.
sync_site_code /usr/src/wordpress/wp-content/themes/tio2 /var/www/html/wp-content/themes/tio2
sync_site_code /usr/src/wordpress/wp-content/plugins/tio2-products /var/www/html/wp-content/plugins/tio2-products

exec /usr/local/bin/docker-entrypoint.sh "$@"

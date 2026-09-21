#!/bin/sh
set -eu
if wp core is-installed; then
    printf '%s\n' 'Preserved existing installation. No settings or content changed.'
    exit 0
fi
wp core install --url="http://127.0.0.1:${WP_PORT}" --title="${WP_SITE_TITLE}" \
  --admin_user="${WP_ADMIN_USER}" --admin_password="${WP_ADMIN_PASSWORD}" \
  --admin_email="${WP_ADMIN_EMAIL}" --skip-email
wp eval-file /workspace/scripts/install.php

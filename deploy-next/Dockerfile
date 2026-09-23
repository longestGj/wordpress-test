FROM wordpress:cli-2.12.0-php8.3 AS wpcli

FROM wordpress:7.1.1-php8.3-apache

COPY --from=wpcli /usr/local/bin/wp /usr/local/bin/wp
COPY wp-content/themes/tio2 /usr/src/wordpress/wp-content/themes/tio2
COPY wp-content/plugins/tio2-products /usr/src/wordpress/wp-content/plugins/tio2-products
COPY data /workspace/data
COPY scripts /workspace/scripts
COPY deploy-next/set-admin-password.php /workspace/deploy-next/set-admin-password.php
COPY deploy-next/entrypoint.sh /usr/local/bin/tio2-next-entrypoint

RUN chmod 0755 /usr/local/bin/tio2-next-entrypoint \
    && printf '\nListen 18080\n' >> /etc/apache2/ports.conf \
    && sed -i 's/<VirtualHost \*:80>/<VirtualHost *:80 *:18080>/' /etc/apache2/sites-available/000-default.conf \
    && grep -q '<VirtualHost \*:80 \*:18080>' /etc/apache2/sites-available/000-default.conf \
    && test -f /usr/src/wordpress/wp-content/themes/tio2/style.css \
    && test -f /usr/src/wordpress/wp-content/plugins/tio2-products/tio2-products.php \
    && test -x /usr/local/bin/wp \
    && chown -R www-data:www-data /usr/src/wordpress/wp-content /workspace

ENTRYPOINT ["/usr/local/bin/tio2-next-entrypoint"]
CMD ["apache2-foreground"]

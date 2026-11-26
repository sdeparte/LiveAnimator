# --- Assets (Webpack Encore 1, Vue 2) ----------------------------------------
FROM node:16-bullseye AS assets
WORKDIR /app
COPY package.json yarn.lock ./
RUN yarn install --frozen-lockfile --non-interactive
COPY webpack.config.js ./
COPY assets ./assets
# Relative: the hub is served on the site's own domain (see the Apache proxy).
ENV MERCURE_PUBLIC_URL=/.well-known/mercure
RUN yarn build

# --- Runtime base (PHP 7.4 + Apache) ----------------------------------------
# PHP 7.4: the version the site runs on today (Symfony 5.2).
FROM php:7.4-apache AS base

COPY --from=mlocati/php-extension-installer:2 /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions intl opcache zip

# Like the old vhost: every request that is not a file goes to index.php, and
# /.well-known/mercure is proxied to the Mercure hub (same domain, so that the
# mercureAuthorization cookie set by Symfony reaches it).
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri 's#/var/www/html#${APACHE_DOCUMENT_ROOT}#g' /etc/apache2/sites-available/*.conf \
 && echo 'ServerName localhost' > /etc/apache2/conf-available/servername.conf \
 && a2enconf servername && a2enmod proxy proxy_http \
 && mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
COPY docker/apache.conf /etc/apache2/conf-available/live-animator.conf
RUN a2enconf live-animator
COPY docker/php.ini $PHP_INI_DIR/conf.d/zz-app.ini

ENV APP_ENV=prod APP_DEBUG=0
WORKDIR /var/www/html

# --- Build: dependencies, cache ----------------------------------------------
FROM base AS build
# Composer 2.2 LTS: symfony/flex 1.12 is not compatible with newer Composer 2.x.
COPY --from=composer:2.2 /usr/bin/composer /usr/local/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1

COPY composer.json composer.lock symfony.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --no-progress --no-interaction

COPY . .
COPY --from=assets /app/public/build public/build
# The permissions are normalised so the image does not depend on the umask of
# the machine that cloned the repository: only var/ and config/jwt/ are
# writable by Apache.
RUN composer dump-autoload --no-dev --classmap-authoritative \
 && php bin/console cache:clear --no-warmup \
 && php bin/console assets:install public \
 && rm -rf var/cache/* var/log/* \
 && mkdir -p config/jwt \
 && chown -R root:root . && chmod -R u=rwX,go=rX . \
 && chown -R www-data:www-data var config/jwt

# --- Final image -------------------------------------------------------------
FROM base
COPY --from=build /var/www/html /var/www/html
COPY --chmod=755 docker/entrypoint.sh /usr/local/bin/live-animator-entrypoint

ENTRYPOINT ["live-animator-entrypoint"]
CMD ["apache2-foreground"]

HEALTHCHECK --interval=30s --timeout=5s --start-period=20s --retries=3 \
  CMD curl -fsS -o /dev/null http://localhost/ || exit 1

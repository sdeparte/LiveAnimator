#!/bin/sh
set -e

# The JWT key pair of the API lives in a volume: created on the first start
# (with JWT_PASSPHRASE), then kept, so that issued tokens stay valid.
if [ ! -f config/jwt/private.pem ]; then
    php bin/console lexik:jwt:generate-keypair --skip-if-exists --no-interaction
fi
chown -R www-data:www-data config/jwt var
chmod 600 config/jwt/private.pem

exec docker-php-entrypoint "$@"

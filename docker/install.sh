#!/bin/sh

openssl genrsa -out var/jwt/private.pem -aes256 -passout pass:pass_phrase 4096
openssl rsa -pubout -in var/jwt/private.pem -out var/jwt/public.pem -passin pass:pass_phrase

composer install --prefer-dist --no-progress --no-suggest

php bin/console doctrine:schema:drop --force
php bin/console doctrine:schema:create
php bin/console doctrine:fixtures:load --no-interaction

php bin/console cache:clear --env=dev --no-warmup
php bin/console cache:clear --env=test --no-warmup
php bin/console cache:clear --env=prod --no-warmup

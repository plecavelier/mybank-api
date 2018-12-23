#!/bin/sh

composer install --prefer-dist --no-progress --no-suggest

php bin/console doctrine:schema:drop --force
php bin/console doctrine:schema:create
php bin/console doctrine:fixtures:load --no-interaction

php bin/console cache:clear --env=dev --no-warmup
php bin/console cache:clear --env=test --no-warmup
php bin/console cache:clear --env=prod --no-warmup

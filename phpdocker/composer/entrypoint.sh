#!/usr/bin/env bash

cd /application || exit
/wait-for-it.sh db:3306
composer install -n
bin/console d:s:u -f -n
bin/console doc:fix:load --no-interaction

exec "$@"

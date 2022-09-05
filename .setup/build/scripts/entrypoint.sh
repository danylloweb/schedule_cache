#!/usr/bin/env sh

printf "\n\nStarting PHP $PHP_VERSION daemon...\n\n";
php-fpm --daemonize

printf "Starting Nginx...\n\n"
set -e

if [[ "$1" == -* ]]; then
    set -- nginx -g daemon off; "$@"
fi

exec "$@"
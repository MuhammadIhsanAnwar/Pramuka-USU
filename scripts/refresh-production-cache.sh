#!/usr/bin/env sh

# Run this from the Laravel project root after each production deployment.
# It refreshes Laravel's cached provider, configuration, and route metadata so
# new Filament panel routes such as /admin and /user are registered.

set -eu

PHP_BIN="${PHP_BIN:-php}"

if [ ! -f artisan ]; then
    echo "Run this script from the Laravel project root (the directory containing artisan)." >&2
    exit 1
fi

"$PHP_BIN" artisan optimize:clear
"$PHP_BIN" artisan package:discover --ansi
"$PHP_BIN" artisan config:cache
"$PHP_BIN" artisan route:cache
"$PHP_BIN" artisan view:cache

echo "\nRegistered admin routes:"
"$PHP_BIN" artisan route:list --path=admin

echo "\nRegistered user routes:"
"$PHP_BIN" artisan route:list --path=user

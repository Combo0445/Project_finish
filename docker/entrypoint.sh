#!/bin/sh
set -e

# Platforms like Railway/Render inject a dynamic $PORT and expect the app to
# listen on it; local docker-compose has no $PORT set, so this defaults to 80.
if [ -n "$PORT" ]; then
    sed -ri "s/^Listen .*/Listen ${PORT}/" /etc/apache2/ports.conf
    sed -ri "s/:80>/:${PORT}>/" /etc/apache2/sites-available/*.conf
fi

if [ ! -f /var/www/html/.env ] && [ -f /var/www/html/.env.example ]; then
    cp /var/www/html/.env.example /var/www/html/.env
fi

if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

php artisan storage:link || true

php artisan config:cache
php artisan route:cache
php artisan view:cache

php artisan migrate --force

exec "$@"

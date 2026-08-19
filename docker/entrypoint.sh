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

# Force mpm_prefork as the only enabled MPM, done here at container start
# (not just at image build time) because something reintroduces mpm_event's
# mods-enabled symlinks between build and container start on Railway.
# This runs as the last filesystem change before Apache launches, so nothing
# after it can undo the fix.
rm -f /etc/apache2/mods-enabled/mpm_event.load /etc/apache2/mods-enabled/mpm_event.conf \
      /etc/apache2/mods-enabled/mpm_worker.load /etc/apache2/mods-enabled/mpm_worker.conf
ln -sf /etc/apache2/mods-available/mpm_prefork.load /etc/apache2/mods-enabled/mpm_prefork.load
ln -sf /etc/apache2/mods-available/mpm_prefork.conf /etc/apache2/mods-enabled/mpm_prefork.conf

echo "=== DEBUG: /etc/apache2/mods-enabled (mpm) ==="
ls -la /etc/apache2/mods-enabled/ 2>&1 | grep -i mpm
echo "=== DEBUG: apache2ctl -M ==="
apache2ctl -M 2>&1
echo "=== END DEBUG ==="

exec "$@"

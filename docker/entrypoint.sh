#!/usr/bin/env bash
set -euo pipefail

APP_DIR=/var/www/html

echo "────────────────────────────────────────────────"
echo " TIPTAP Africa – container startup"
echo "────────────────────────────────────────────────"

if [ ! -f "${APP_DIR}/.env" ]; then
    if [ -f "${APP_DIR}/.env.docker" ]; then
        echo "[env] Copying .env.docker → .env"
        cp "${APP_DIR}/.env.docker" "${APP_DIR}/.env"
    else
        echo "[env] Copying .env.example → .env"
        cp "${APP_DIR}/.env.example" "${APP_DIR}/.env"
    fi
fi

cd "${APP_DIR}"

if grep -q '^APP_KEY=$' .env 2>/dev/null || ! grep -q '^APP_KEY=base64:' .env 2>/dev/null; then
    echo "[key] Generating application key..."
    php artisan key:generate --force --no-interaction
fi

if [ ! -L public/storage ]; then
    echo "[storage] Creating storage symlink..."
    php artisan storage:link --no-interaction || true
fi

if [ -d /opt/tiptap-build-assets ]; then
    echo "[assets] Syncing Vite build assets..."
    mkdir -p public/build
    cp -a /opt/tiptap-build-assets/. public/build/
fi

DB_HOST="${DB_HOST:-mysql}"
DB_PORT="${DB_PORT:-3306}"
DB_DATABASE="${DB_DATABASE:-taptap}"
DB_USERNAME="${DB_USERNAME:-root}"
DB_PASSWORD="${DB_PASSWORD:-}"

echo "[db] Waiting for MySQL at ${DB_HOST}:${DB_PORT}..."
MAX_TRIES=60
COUNT=0
until php -r "
    try {
        \$pdo = new PDO(
            'mysql:host=${DB_HOST};port=${DB_PORT};dbname=${DB_DATABASE}',
            '${DB_USERNAME}',
            '${DB_PASSWORD}'
        );
        exit(0);
    } catch (Exception \$e) {
        exit(1);
    }
" 2>/dev/null; do
    COUNT=$((COUNT + 1))
    if [ $COUNT -ge $MAX_TRIES ]; then
        echo "[db] ERROR: MySQL not ready after ${MAX_TRIES} attempts. Aborting."
        exit 1
    fi
    echo "[db] Attempt ${COUNT}/${MAX_TRIES} – retrying in 2s..."
    sleep 2
done
echo "[db] MySQL is ready!"

echo "[migrate] Running migrations..."
php artisan migrate --force --no-interaction

echo "[perms] Setting storage permissions..."
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
if [ -d storage/docker-ssh ]; then
    chown -R www-data:www-data storage/docker-ssh
    chmod 700 storage/docker-ssh
    [ -f storage/docker-ssh/docker_control ] && chmod 600 storage/docker-ssh/docker_control
    [ -f storage/docker-ssh/docker_control.pub ] && chmod 644 storage/docker-ssh/docker_control.pub
fi

if [ -S /var/run/docker.sock ]; then
    DOCKER_GID=$(stat -c '%g' /var/run/docker.sock)
    if ! getent group docker >/dev/null 2>&1; then
        addgroup -g "${DOCKER_GID}" docker 2>/dev/null || addgroup docker
    fi
    adduser www-data docker 2>/dev/null || true
fi

APP_ENV="${APP_ENV:-production}"
if [ "${APP_ENV}" = "production" ]; then
    echo "[cache] Caching config / routes / views..."
    php artisan config:cache --no-interaction
    php artisan route:cache --no-interaction
    php artisan view:cache --no-interaction
fi

echo "────────────────────────────────────────────────"
echo " Startup complete – launching PHP-FPM"
echo "────────────────────────────────────────────────"

exec "$@"

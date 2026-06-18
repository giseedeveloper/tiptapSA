#!/bin/bash
set -e

# TIPTAP SOUTH AFRICA – Deploy Script
# VPS: 139.59.151.111 (DigitalOcean – Cape Town)
# Project: /root/TIPTAP
# Domain: tiptapafrica.co.za
#
# Requires SSH key access to root@139.59.151.111
# Optional env overrides: TIPTAP_SA_HOST, TIPTAP_SA_USER, TIPTAP_SA_PATH, TIPTAP_SA_BRANCH

HOST="${TIPTAP_SA_HOST:-139.59.151.111}"
USER="${TIPTAP_SA_USER:-root}"
PROJECT_PATH="${TIPTAP_SA_PATH:-/root/TIPTAP}"
BRANCH="${TIPTAP_SA_BRANCH:-main}"

echo "=== TIPTAP SOUTH AFRICA DEPLOY ==="
echo "Host: $HOST"
echo "Path: $PROJECT_PATH"
echo ""

echo "--- Pushing to GitHub ---"
git push origin "$BRANCH"

echo "--- Connecting to VPS ---"
ssh -o StrictHostKeyChecking=no "${USER}@${HOST}" "
    set -e
    echo '--- Pulling latest code ---'
    cd ${PROJECT_PATH}
    git pull origin ${BRANCH}

    echo '--- Building Docker containers ---'
    docker compose build --no-cache app queue
    docker compose up -d

    echo '--- Syncing public assets into Docker volume ---'
    CID=\$(docker create tiptap-app)
    docker cp \"\$CID:/var/www/html/public/build\" /tmp/tiptap_build_sync
    docker cp \"\$CID:/var/www/html/public/images/flags\" /tmp/tiptap_flags_sync
    docker rm \"\$CID\"
    docker cp /tmp/tiptap_build_sync/. tiptap_app:/var/www/html/public/build/
    docker cp /tmp/tiptap_flags_sync/. tiptap_app:/var/www/html/public/images/flags/
    rm -rf /tmp/tiptap_build_sync /tmp/tiptap_flags_sync

    echo '--- Syncing app code into running container ---'
    docker cp resources/. tiptap_app:/var/www/html/resources/
    docker cp app/. tiptap_app:/var/www/html/app/
    docker cp routes/. tiptap_app:/var/www/html/routes/
    docker cp config/. tiptap_app:/var/www/html/config/

    echo '--- Migrating + caching Laravel ---'
    docker exec tiptap_app php artisan migrate --force
    docker exec tiptap_app php artisan optimize:clear
    docker exec tiptap_app php artisan config:cache
    docker exec tiptap_app php artisan route:cache
    docker exec tiptap_app php artisan view:cache

    echo '--- Checking status ---'
    docker ps --format '{{.Names}} {{.Status}}'

    echo '--- Done! ---'
"

echo ""
echo "=== DEPLOY COMPLETE ==="
echo "Visit: https://tiptapafrica.co.za"

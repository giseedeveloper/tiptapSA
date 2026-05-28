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

    echo '--- Migrating + caching Laravel ---'
    docker exec tiptap_app php artisan migrate --force
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

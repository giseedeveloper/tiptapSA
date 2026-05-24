#!/bin/bash
set -e

# TIPTAP SOUTH AFRICA – Deploy Script
# VPS: 139.59.151.111 (DigitalOcean – Cape Town)
# Project: /root/TIPTAP
# Domain: tiptapafrica.co.za
#
# HITAJI: sshpass (brew install sshpass)

HOST="139.59.151.111"
USER="root"
PASS="HMF\$_h9TMN6XT.x"
PROJECT_PATH="/root/TIPTAP"
BRANCH="main"

echo "=== TIPTAP SOUTH AFRICA DEPLOY ==="
echo "Host: $HOST"
echo "Path: $PROJECT_PATH"
echo ""

# Check if sshpass is installed
if ! command -v sshpass &> /dev/null; then
    echo "sshpass is not installed. Install it with: brew install sshpass"
    exit 1
fi

# Push latest code to GitHub first
echo "--- Pushing to GitHub ---"
git push origin $BRANCH

# SSH and deploy
echo "--- Connecting to VPS ---"
sshpass -p "$PASS" ssh -o StrictHostKeyChecking=no "$USER@$HOST" "
    set -e
    echo '--- Pulling latest code ---'
    cd $PROJECT_PATH
    git pull origin $BRANCH

    echo '--- Building Docker containers ---'
    docker compose build --no-cache app queue
    docker compose up -d

    echo '--- Caching Laravel ---'
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

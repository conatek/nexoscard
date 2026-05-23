#!/bin/bash
# Deploy NexosCard to production (AWS EC2)
# Usage: ./deploy-nexoscard-pro.sh [--skip-build]

set -e

SERVER="ubuntu@3.229.237.6"
KEY="~/servers/cntk-nexoscard-pro.pem"
REMOTE_PATH="/var/www/nexoscard"

echo "=== Deploy NexosCard PRO ==="
echo ""

# Step 1: Build frontend (unless --skip-build)
if [ "$1" != "--skip-build" ]; then
    echo "→ Building frontend..."
    npm run build
    echo ""

    echo "→ Uploading build to server..."
    scp -i $KEY -r public/build/ $SERVER:$REMOTE_PATH/public/
    echo ""
else
    echo "→ Skipping frontend build (--skip-build)"
    echo ""
fi

# Step 2: Pull latest code and run server tasks
echo "→ Pulling code and running server tasks..."
ssh -i $KEY $SERVER "cd $REMOTE_PATH && \
    git pull origin main && \
    php artisan migrate --force && \
    php artisan config:clear && \
    php artisan view:clear && \
    sudo supervisorctl restart nexoscard-worker:*"

echo ""
echo "=== Deploy completed! ==="
echo "→ https://nexoscard.com"

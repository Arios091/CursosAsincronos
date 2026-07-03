#!/bin/bash
set -e

APP_DIR=/var/www/sgd
ENV=$1

if [ "$ENV" != "production" ]; then
    echo "Usage: $0 production"
    exit 1
fi

cd $APP_DIR

git pull origin main

# Production env
cp .env.production .env

# Composer (no dev)
composer install --optimize-autoloader --no-dev

# NPM build
npm ci --production
npm run production

# Laravel caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Storage link (si no existe)
php artisan storage:link || true

# Permissions
chown -R apache:apache storage bootstrap/cache public/storage
chmod -R 775 storage bootstrap/cache public/storage

# SELinux (CentOS 7)
chcon -R -t httpd_sys_rw_content_t storage bootstrap/cache public/storage 2>/dev/null || true

# Migrate
php artisan migrate --force

# Clear expired pending users
php artisan clean:expired-pending-users

echo "Deploy complete."

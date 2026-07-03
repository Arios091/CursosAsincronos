#!/bin/bash
set -e

APP_DIR=/var/www/sgd
ENV=$1

if [ "$ENV" != "production" ]; then
    echo "Usage: $0 production"
    exit 1
fi

cd $APP_DIR

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

# Permissions
chown -R apache:apache storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Migrate
php artisan migrate --force

echo "Deploy complete."

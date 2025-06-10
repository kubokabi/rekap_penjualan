#!/bin/bash

cd /home/pageidco/rekap.page-id.com || exit

echo "Pulling latest code..."
git pull origin main

echo "Installing dependencies..."
composer install --no-dev --optimize-autoloader

echo "Running artisan commands..."
php artisan migrate --force
php artisan config:cache
php artisan route:cache

echo "Deployment finished at $(date)"


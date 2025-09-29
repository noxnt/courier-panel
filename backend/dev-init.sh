#!/bin/bash
set -e

echo "Install packenges"
composer install

echo "Running migrations..."
php artisan migrate --force

echo "Seeding database..."
php artisan db:seed --class=DatabaseSeeder
php artisan db:seed --class=CourierSeeder

echo "Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear

echo "All done. Development environment is ready."

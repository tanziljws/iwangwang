#!/bin/bash
# Script to clear all Laravel caches
# Run this after deploying to Railway or making route/middleware changes

echo "Clearing Laravel caches..."

php artisan route:clear
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan optimize:clear

echo "All caches cleared successfully!"


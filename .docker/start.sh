#!/bin/bash

php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

php artisan config:cache

php artisan migrate --force

php-fpm -D

nginx -g "daemon off;"
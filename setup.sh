#!/usr/bin/env bash
set -e
[ -f .env ] || cp .env.example .env
composer install
php artisan key:generate
php artisan storage:link || true
php artisan migrate --seed
npm install
npm run build
echo "Setup selesai."

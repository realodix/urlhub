#!/bin/sh

sleep 5

composer install

php artisan key:generate

php artisan migrate --seed

npm install && npm run dev

php artisan serve --host 0.0.0.0 --port 8000

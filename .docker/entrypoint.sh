#!/bin/bash

#On error no such file entrypoint.sh, execute in terminal - dos2unix .docker\entrypoint.sh
composer install
php artisan key:generate
php artisan migrate:fresh --seed

php-fpm
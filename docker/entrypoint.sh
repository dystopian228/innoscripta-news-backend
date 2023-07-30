php artisan key:generate
php artisan cache:clear
php artisan config:clear
php artisan migrate

php artisan serve --port=$PORT --host=0.0.0.0 --env=.env

if [ ! -f ".env" ]; then 
    cp .env.example .env
    sed -s -i -e "s/DB_CONNECTION=/DB_CONNECTION=${DB_CONNECTION}/" /var/www/html/.env
    sed -s -i -e "s/DB_HOST=/DB_HOST=${DB_HOST}/" /var/www/html/.env
    sed -s -i -e "s/DB_PORT=/DB_PORT=${DB_PORT}/" /var/www/html/.env
    sed -s -i -e "s/DB_DATABASE=/DB_DATABASE=${DB_DATABASE}/" /var/www/html/.env
    sed -s -i -e "s/DB_PASSWORD=/DB_PASSWORD=${DB_PASSWORD}/" /var/www/html/.env
    sed -s -i -e "s/DB_USERNAME=/DB_USERNAME=${DB_USERNAME}/" /var/www/html/.env
    sed -s -i -e "s/APP_ENV=/APP_ENV=${APP_ENV}/" /var/www/html/.env
    sed -s -i -e "s/APP_DEBUG=/APP_DEBUG=${APP_DEBUG}/" /var/www/html/.env
    sed -s -i -e "s/APP_NAME=/APP_NAME=${APP_NAME}/" /var/www/html/.env
    sed -s -i -e "s/NEWS_API_BASE_URL=/NEWS_API_BASE_URL=${NEWS_API_BASE_URL}/" /var/www/html/.env
    sed -s -i -e "s/NEWS_API_API_KEY=/NEWS_API_API_KEY=${NEWS_API_API_KEY}/" /var/www/html/.env
    sed -s -i -e "s/THE_GUARDIAN_BASE_URL=/THE_GUARDIAN_BASE_URL=${THE_GUARDIAN_BASE_URL}/" /var/www/html/.env
    sed -s -i -e "s/THE_GUARDIAN_API_KEY=/THE_GUARDIAN_API_KEY=${THE_GUARDIAN_API_KEY}/" /var/www/html/.env
    sed -s -i -e "s/NYTIMES_BASE_URL=/NYTIMES_BASE_URL=${NYTIMES_BASE_URL}/" /var/www/html/.env
    sed -s -i -e "s/NYTIMES_API_KEY=/NYTIMES_API_KEY=${NYTIMES_API_KEY}/" /var/www/html/.env
fi

cat > cronjobs << EOF

SHELL=/bin/bash

* * * * * /usr/local/bin/php /var/www/html/artisan schedule:run >> /var/www/html/storage/logs/cron-logs.log 2>&1

EOF

/usr/sbin/cron
crontab cronjobs

composer install --no-dev --optimize-autoloader
php artisan key:generate
php artisan cache:clear
php artisan config:clear
php artisan migrate --force

php artisan serve --port=$PORT --host=0.0.0.0

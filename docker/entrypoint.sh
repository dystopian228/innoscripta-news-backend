cat > cronjobs << EOF

* * * * * cd /var/www/html && php artisan schedule:run >> /var/www/html/storage/logs/cron-logs.log 2>&1

EOF

/usr/sbin/cron
crontab cronjobs

if [ ! -f ".env" ]; then 
    cp .env.example .env
fi

composer install --no-dev --optimize-autoloader
php artisan key:generate
php artisan cache:clear
php artisan config:clear
php artisan migrate

php artisan serve --port=$PORT --host=0.0.0.0

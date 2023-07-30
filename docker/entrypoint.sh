cat > cronjobs << EOF

* * * * * cd /path-to-your-project && php artisan schedule:run >> /var/www/html/storage/logs/cron-logs.log 2>&1

EOF

/usr/sbin/cron
crontab cronjobs

php artisan key:generate
php artisan cache:clear
php artisan config:clear
php artisan migrate

php artisan serve --port=$PORT --host=0.0.0.0

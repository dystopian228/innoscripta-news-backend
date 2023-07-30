FROM php:8.1-fpm

# Install dependencies
RUN apt-get update && \
    apt-get install -y \
        libzip-dev \
        zip \
        unzip \
        libcurl4-gnutls-dev \
        libonig-dev \
        libpq-dev \
        cron \
        && docker-php-ext-install pdo pgsql bcmath

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www/html

# Copy application files and directories to the container
COPY . .

# Install application dependencies
RUN composer install --no-dev --prefer-dist --optimize-autoloader --no-scripts --no-progress --no-suggest

# Set permissions for storage and bootstrap cache directories
RUN chown -R www-data:www-data storage bootstrap/cache && \
    chmod -R 775 storage bootstrap/cache

EXPOSE 8080

ENTRYPOINT ["./docker/entrypoint.sh"]

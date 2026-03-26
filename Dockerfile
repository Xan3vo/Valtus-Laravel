# Laravel 12 + PHP 8.2 Dockerfile optimized for Railway
FROM php:8.2-fpm

# System dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev unzip git libonig-dev libpng-dev libxml2-dev curl zip zlib1g-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl bcmath gd

# Install composer
COPY --from=composer:2.8 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/valtus

# Copy files
COPY . /var/www/valtus

# Debug: verify files are copied correctly
RUN ls -al /var/www/valtus
RUN test -f /var/www/valtus/composer.json && echo "composer.json found" || (echo "composer.json missing" && false)

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Setup app
RUN cp .env.example .env || true
RUN php artisan key:generate --force
RUN php artisan config:cache
RUN php artisan route:cache
RUN php artisan view:cache

RUN chown -R www-data:www-data /var/www/valtus/storage /var/www/valtus/bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]

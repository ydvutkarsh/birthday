FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    ca-certificates \
    libcurl4-openssl-dev \
    libzip-dev \
    && docker-php-ext-install curl pdo_mysql zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN chmod -R 775 storage bootstrap/cache

CMD php artisan migrate --force \
    && php artisan db:seed --class=UserSeeder --force \
    && (php artisan storage:link || true) \
    && php artisan serve --host=0.0.0.0 --port=${PORT:-10000}
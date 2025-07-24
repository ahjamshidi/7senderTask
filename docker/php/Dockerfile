FROM php:8.3-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    git unzip zip libicu-dev libpq-dev libonig-dev libxml2-dev \
    libzip-dev curl libcurl4-openssl-dev libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-install bcmath pdo pdo_pgsql intl zip opcache gd \
    && docker-php-ext-enable bcmath

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

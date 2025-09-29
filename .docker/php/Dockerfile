FROM php:8.3-fpm

# Outils & lib nécessaires
RUN apt-get update && apt-get install -y --no-install-recommends \
    git unzip libicu-dev libzip-dev libonig-dev libxml2-dev default-mysql-client \
 && docker-php-ext-install intl zip opcache pdo_mysql \
 && rm -rf /var/lib/apt/lists/*

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Config PHP
COPY php.ini /usr/local/etc/php/conf.d/php.ini

WORKDIR /var/www/html

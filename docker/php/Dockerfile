FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    git unzip curl libicu-dev libzip-dev libpng-dev libonig-dev \
    && docker-php-ext-install \
        intl \
        pdo \
        pdo_mysql \
        zip \
        opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY docker/php/php.ini /usr/local/etc/php/conf.d/custom.ini

WORKDIR /var/www/app

RUN chown -R www-data:www-data /var/www/app

USER www-data

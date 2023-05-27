FROM composer:latest AS composer
FROM php:8.1-fpm-alpine AS php

RUN apk add --update linux-headers
RUN apk add --no-cache git bash $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install bcmath


# Create a new user 'myuser'
RUN addgroup -g 1000 myuser && adduser -G myuser -g myuser -s /bin/sh -D myuser

# Switch to 'myuser'
USER myuser

COPY --from=composer /usr/bin/composer /usr/local/bin/composer
WORKDIR /var/www/html

COPY src/composer.json src/composer.lock ./
RUN composer install --no-dev --optimize-autoloader

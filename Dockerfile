FROM php:7.4-fpm

RUN docker-php-ext-install pdo_mysql

RUN apt-get update && apt-get install zip -y


COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

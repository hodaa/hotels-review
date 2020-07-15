FROM php:7.4-fpm

RUN docker-php-ext-install pdo_mysql

#RUN apt-get update && apt-get install zip -y
RUN pecl install apcu

RUN apt-get update && \
apt-get install -y \
zlib1g-dev && apt-get install -y wget

#RUN apt-get install libzip-dev
RUN docker-php-ext-install zip

RUN docker-php-ext-enable apcu

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

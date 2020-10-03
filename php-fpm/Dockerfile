FROM php:5.6-fpm-alpine

RUN apk add --no-cache \
        make \
        pkgconfig \
        build-base \
        autoconf

# bash is needed for the CakePHP console
RUN apk add --no-cache imagemagick imagemagick-dev bash

RUN pecl install imagick-3.4.4 apcu-4.0.11 && docker-php-ext-enable imagick && docker-php-ext-enable apcu && docker-php-ext-install mysql mysqli pdo pdo_mysql

COPY ./php.ini /usr/local/etc/php/conf.d/whyjustrun.ini

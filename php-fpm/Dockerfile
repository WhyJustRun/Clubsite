
FROM php:5.6-fpm-alpine

RUN apk add --no-cache \
        make \
        pkgconfig \
        build-base \
        autoconf

RUN pecl install apcu-4.0.11 && docker-php-ext-enable apcu && docker-php-ext-install mysql mysqli pdo pdo_mysql

# bash is needed for the CakePHP console
RUN apk add --no-cache imagemagick bash

COPY ./php.ini /usr/local/etc/php/conf.d/whyjustrun.ini

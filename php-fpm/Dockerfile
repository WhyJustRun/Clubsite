FROM php:5.6-fpm-alpine

RUN apk add --no-cache \
        make \
        pkgconfig \
        build-base \
        autoconf

# bash is needed for the CakePHP console
RUN apk add --no-cache ghostscript imagemagick imagemagick-dev bash nodejs npm

RUN pecl install imagick-3.4.4 apcu-4.0.11 && docker-php-ext-enable imagick && docker-php-ext-enable apcu && docker-php-ext-install mysql mysqli pdo pdo_mysql

# Workaround for npm on Alpine "Error: could not get uid/gid" (https://stackoverflow.com/questions/52196518/could-not-get-uid-gid-when-building-node-docker)
RUN npm config set unsafe-perm true

# used for minification in production use
RUN npm install -g clean-css-cli requirejs

COPY ./php.ini /usr/local/etc/php/conf.d/whyjustrun.ini

FROM php:8.3-fpm-alpine

RUN apk update; \
    apk upgrade;

# Debian args
ARG NONROOT_USER=www-data

ARG PHP_VERSION=83
ENV PHP_VERSION=${PHP_VERSION}

RUN apk add --no-cache \
    linux-headers \
    icu-dev \
    zlib-dev \ 
    curl \
    #php-mysqli \
    php-pdo_mysql \
    #php-intl \
    libzip-dev \
    libxml2-dev \
    php-pecl-imagick \
    php-iconv \
    php-ctype \
    php-dom \
    #php-pecl-xdebug \
    # For users permissions
    shadow \
    zip \
    unzip \
    wget \
    nano \
    autoconf \
    g++ \
    make

    # && ln -s /usr/bin/php${PHP_VERSION} 

RUN pecl install xdebug \ 
    docker-php-ext-enable xdebug \
    && docker-php-ext-install intl mysqli

# add non-root user
RUN usermod -u 1000 ${NONROOT_USER} \
    && groupmod -g 1000 ${NONROOT_USER}

# XDEBUG conf
COPY xdebug/xdebug.ini /usr/local/etc/php/conf.d/99_xdebug.ini

# Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN chown -R ${NONROOT_USER}:${NONROOT_USER} /var/www/html

# Defines the work directory
WORKDIR /var/www/html

EXPOSE 9000

CMD [ "php-fpm", "-F" ]
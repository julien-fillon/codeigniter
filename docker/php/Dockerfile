FROM php:8.3-fpm-alpine

RUN apk update; \
    apk upgrade;

# Debian args
ARG NONROOT_USER=www-data

ARG PHP_VERSION=83
ENV PHP_VERSION=${PHP_VERSION}

RUN apk add --no-cache \
    libpng-dev \
    libwebp-dev \
    linux-headers \
    icu-dev \
    zlib-dev \ 
    curl \
    php-pdo_mysql \
    libzip-dev \
    libxml2-dev \
    php-iconv \
    php-ctype \
    php-dom \
    php-gd \
    # For users permissions
    shadow \
    zip \
    unzip \
    wget \
    nano \
    autoconf \
    g++ \
    make \
    imagemagick \
    imagemagick-dev

RUN pecl install xdebug \ 
    docker-php-ext-enable xdebug \
    && docker-php-ext-install intl mysqli

# if use imagick
# Installation propre d'Imagick via PECL
RUN pecl install imagick \
    && docker-php-ext-enable imagick \
    # Parce que docker-php-ext-enable imagick ne parvient pas à activer extension=imagick.so dans php.ini
    && echo "extension=imagick.so" > /usr/local/etc/php/conf.d/imagick.ini

# if use gd
RUN docker-php-ext-configure gd --with-webp && \
    docker-php-ext-install gd

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
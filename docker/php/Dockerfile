FROM php:8.3-fpm-alpine

RUN apk update; \
    apk upgrade;

# Debian args
ARG NONROOT_USER=www-data

ARG PHP_VERSION=83
ENV PHP_VERSION=${PHP_VERSION}

# Install dependencies and PHP extensions
# RUN apk add --no-cache \
#     icu-dev \
#     libzip-dev \
#     imagemagick-dev \
#     shadow \
#     zlib-dev \
#     && docker-php-ext-install \
#     pdo_mysql \
#     intl \
#     zip \
#     && pecl install imagick xdebug \
#     && docker-php-ext-enable imagick xdebug

RUN apk add --no-cache \
    imagemagick \
    # For users permissions
    shadow \
    zip \
    zlib 

RUN docker-php-ext-configure intl && \
    docker-php-ext-install \
    intl

# add non-root user
RUN usermod -u 1000 ${NONROOT_USER} \
    && groupmod -g 1000 ${NONROOT_USER}

# XDEBUG conf
COPY xdebug/xdebug.ini /etc/php${PHP_VERSION}/conf.d/99_xdebug.ini

RUN chown -R ${NONROOT_USER}:${NONROOT_USER} /var/www/html

# Defines the work directory
WORKDIR /var/www/html

EXPOSE 9000

CMD [ "php-fpm", "-F" ]
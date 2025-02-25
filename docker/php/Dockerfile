FROM php:8.3-fpm-alpine

RUN apk update; \
    apk upgrade;

# Debian args
ARG NONROOT_USER=www-data

ARG PHP_VERSION=83
ENV PHP_VERSION=${PHP_VERSION}

RUN apk add --no-cache \
    php-mysqli \
    php-pdo_mysql \
    php-intl \
    php-pecl-imagick \
    php-iconv \
    php-tokenizer \
    php-session \
    php-pecl-xdebug \
    # For users permissions
    shadow \
    zip \
    zlib \
    && ln -s /usr/bin/php${PHP_VERSION} 

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
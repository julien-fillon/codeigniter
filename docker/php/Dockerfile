FROM alpine:latest

RUN apk update; \
    apk upgrade;

# Debian args
ARG NONROOT_USER=www-data

ARG PHP_VERSION=83
ENV PHP_VERSION=${PHP_VERSION}

# Met à jour les dépôts et installe PHP 8.3 et Apache
RUN apk add --no-cache \
    apache2 \
    apache2-proxy \
    apache2-ssl \
    apache2-http2 \
    curl \
    imagemagick \
    php${PHP_VERSION} \
    php${PHP_VERSION}-apache2 \
    php${PHP_VERSION}-cli \
    php${PHP_VERSION}-common \
    php${PHP_VERSION}-opcache \
    php${PHP_VERSION}-mbstring \
    php${PHP_VERSION}-gd \
    php${PHP_VERSION}-intl \
    php${PHP_VERSION}-curl \
    php${PHP_VERSION}-mysqli \
    php${PHP_VERSION}-pdo_mysql \
    php${PHP_VERSION}-xml \
    php${PHP_VERSION}-zip \
    php${PHP_VERSION}-tokenizer \
    php${PHP_VERSION}-ctype \
    php${PHP_VERSION}-session \
    php${PHP_VERSION}-pecl-imagick \
    # Xdebug
    php${PHP_VERSION}-pecl-xdebug \
    # For users permissions
    shadow \
    zip \
    zlib \
    && ln -s /usr/bin/php${PHP_VERSION} \
    && mkdir -p /run/apache2 /var/www/html \
    && chown -R apache:apache /var/www/html

# add non-root user
RUN useradd -r -u 1000 -g ${NONROOT_USER} ${NONROOT_USER} \
    && usermod -u 1000 ${NONROOT_USER} \
    && groupmod -g 1000 ${NONROOT_USER}

# XDEBUG conf
COPY xdebug/xdebug.ini /etc/php${PHP_VERSION}/conf.d/99_xdebug.ini

# Configuration Apache
RUN mkdir -p /etc/apache2/sites-available /etc/apache2/sites-enabled
COPY apache/httpd.conf /etc/apache2/

# Virtual Host Apache configuration for codeignite
COPY apache/codeigniter.conf /etc/apache2/sites-available/codeigniter.conf
RUN ln -s /etc/apache2/sites-available/codeigniter.conf /etc/apache2/sites-enabled/codeigniter.conf

#RUN chown -R ${NONROOT_USER}:${NONROOT_USER} /var/log/apache2 && chmod -R 755 /var/log/apache2

RUN chown -R ${NONROOT_USER}:${NONROOT_USER} /var/www/html

# Defines the work directory
WORKDIR /var/www/html

# RUN mkdir -p /var/log/apache2 /var/www/logs /var/www/html \
#     && chmod -R 755 /var/www/logs \
#     && chmod -R 755 /var/www/html \
#     && chmod -R 755 /var/log/apache2 \
#     && chown -R ${NONROOT_USER}:${NONROOT_USER} /var/www/html \
#     && chown -R ${NONROOT_USER}:${NONROOT_USER} /var/www/logs \
#     && chown -R ${NONROOT_USER}:${NONROOT_USER} /var/www/logs

EXPOSE 8480

# Order to start Apache in Foreground mode
CMD ["httpd", "-D", "FOREGROUND"]
FROM httpd:2.4-alpine

RUN apk update \
    apk upgrade

# Debian args
ARG NONROOT_USER=www-data

RUN apk add --no-cache \
    #apache2-proxy \
    #apache2-ssl \
    #apache2-http2 \
    # For users permissions
    shadow 

# add non-root user
RUN usermod -u 1000 ${NONROOT_USER} \
    && groupmod -g 1000 ${NONROOT_USER}

RUN mkdir -p /var/log/apache2 && \
    chown -R ${NONROOT_USER}:${NONROOT_USER} /var/log/apache2

# Configuration Apache
RUN mkdir -p /usr/local/apache2/sites-available /usr/local/apache2/sites-enabled
COPY conf/httpd.conf /usr/local/apache2/conf/httpd.conf

# Virtual Host Apache configuration for codeignite
COPY conf/codeigniter.conf /usr/local/apache2/sites-available/codeigniter.conf
RUN ln -s /usr/local/apache2/sites-available/codeigniter.conf /usr/local/apache2/sites-enabled/codeigniter.conf

# Defines the work directory
WORKDIR /var/www/html

RUN chown -R ${NONROOT_USER}:${NONROOT_USER} /var/www/html

# Order to start Apache in Foreground mode
CMD ["httpd", "-D", "FOREGROUND"]

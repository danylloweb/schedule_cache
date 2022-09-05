#---------------------------------------------------
# FINAL IMAGE
#---------------------------------------------------

# Image
FROM php:8.0-fpm

# Variables
ENV DOCUMENT_ROOT /var/www/html
ENV TZ=America/Sao_Paulo

# Install dependencies
RUN apk add --no-cache \
        $PHPIZE_DEPS \
        nginx \
        unzip \
        git \
        pkgconfig \
        icu-dev \
        curl-dev \
        openssl-dev \
        libxml2-dev \
        libzip-dev \
        oniguruma-dev \
        readline-dev \
        libmcrypt-dev \
        libxslt-dev \
        libmemcached-dev \
        libpng-dev \
        freetype-dev \
        libjpeg-turbo-dev \
    && docker-php-ext-install opcache \
    && pecl install memcached mcrypt redis mongodb apcu \
    && echo "extension=mongodb.so" > $PHP_INI_DIR/conf.d/mongo.ini \
    && docker-php-ext-enable memcached mcrypt opcache redis mongodb apcu \
    && docker-php-ext-configure gd  \
    && docker-php-ext-configure zip  \
    && docker-php-ext-install bcmath \
    && docker-php-ext-install -j "$(nproc)" mysqli pdo_mysql intl zip xsl soap gd \
    && docker-php-source delete \
    && apk del --no-cache \
        gcc \
        g++ \
        make \
        perl \
        autoconf \
        dpkg-dev \
        dpkg \
        file \
        libc-dev \
        pkgconf \
        re2c


# Tuning OS
RUN echo 'net.ipv4.ip_local_port_range = 12000 65535' >> /etc/sysctl.conf
RUN echo 'fs.file-max = 1048576' >> /etc/sysctl.conf
RUN mkdir /etc/security/
RUN echo '*                soft    nofile          1048576' >> /etc/security/limits.conf
RUN echo '*                hard    nofile          1048576' >> /etc/security/limits.conf
RUN echo 'root             soft    nofile          1048576' >> /etc/security/limits.conf
RUN echo 'root             hard    nofile          1048576' >> /etc/security/limits.conf

# Set Timezone
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Workdir
WORKDIR $DOCUMENT_ROOT

# Remove NGINX old
RUN rm -f /etc/nginx/conf.d/*

# Copy app and configs
COPY . $DOCUMENT_ROOT
COPY .setup/build/nginx/nginx.conf /etc/nginx/nginx.conf
COPY .setup/build/php/php.ini /usr/local/etc/php/conf.d/app.ini
COPY .setup/build/php/php-fpm-www.conf /usr/local/etc/php-fpm.d/www.conf

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
RUN composer install --no-dev --no-interaction --no-scripts

# Logs to stdout
RUN ln -sf /dev/stdout /var/log/nginx/access.log && ln -sf /dev/stderr /var/log/nginx/error.log

# Expose port
EXPOSE 80

# Start APP
CMD ["nginx", "-g", "daemon off;"]
RUN chmod +x .setup/build/scripts/entrypoint.sh
ENTRYPOINT [".setup/build/scripts/entrypoint.sh"]

FROM php:7.0-apache

# Install packages
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git \
        curl \
        sudo \
    && rm -rf /var/lib/apt/lists/*

# PHP extensions
ENV APCU_VERSION 5.1.7
RUN buildDeps=" \
        libicu-dev \
        zlib1g-dev \
    " \
    && apt-get update \
    && apt-get install -y --no-install-recommends \
        $buildDeps \
        zlib1g \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install \
        intl \
        mbstring \
        pdo_mysql \
        zip \
    && apt-get purge -y --auto-remove $buildDeps
RUN pecl install \
        apcu-$APCU_VERSION \
    && docker-php-ext-enable --ini-name 05-opcache.ini \
        opcache \
    && docker-php-ext-enable --ini-name 20-apcu.ini \
        apcu

# Apache config
RUN a2enmod rewrite
ADD docker/apache/vhost.conf /etc/apache2/sites-available/000-default.conf

# PHP config
ADD docker/php/php.ini /usr/local/etc/php/php.ini

# Add the application
ADD . /app
WORKDIR /app

# Fix permissions (useful if the host is Windows)
RUN chmod +x docker/apache/start_safe_perms docker/composer/get-composer.sh docker/utils/init-owner.sh docker/utils/wait-for-it.sh docker/install.sh docker/start.sh

# Install composer
RUN ./docker/composer/get-composer.sh \
    && mv composer.phar /usr/bin/composer \
    && composer global require "hirak/prestissimo:^0.3"

CMD ["/app/docker/start.sh"]

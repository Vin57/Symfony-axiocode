FROM php:7.4-fpm

ENV PHP_APCU_VERSION 5.1.20
ENV PHP_XDEBUG_VERSION 3.0.4
ENV GIT_USERMAIL vincent.philippe18@gmail.com
ENV GIT_USERNAME Vin57

RUN apt-get update \
    && apt-get install -y \
        libicu-dev \
        zlib1g-dev \
        libonig-dev \
        libzip-dev \
        wget \
        git \
    && docker-php-source extract \
    && curl -L -o /tmp/apcu-$PHP_APCU_VERSION.tgz https://pecl.php.net/get/apcu-$PHP_APCU_VERSION.tgz \
    && curl -L -o /tmp/xdebug-$PHP_XDEBUG_VERSION.tgz http://xdebug.org/files/xdebug-$PHP_XDEBUG_VERSION.tgz \
    && tar xfz /tmp/apcu-$PHP_APCU_VERSION.tgz \
    && tar xfz /tmp/xdebug-$PHP_XDEBUG_VERSION.tgz \
    && rm -r \
        /tmp/apcu-$PHP_APCU_VERSION.tgz \
        /tmp/xdebug-$PHP_XDEBUG_VERSION.tgz \
    && mv apcu-$PHP_APCU_VERSION /usr/src/php/ext/apcu \
    && mv xdebug-$PHP_XDEBUG_VERSION /usr/src/php/ext/xdebug \
    && touch /usr/src/php-available-exts \
    && sed -i '$ a apcu' /usr/src/php-available-exts \
    && sed -i '$ a xdebug' /usr/src/php-available-exts \
    && docker-php-ext-install \
        apcu \
        intl \
        mbstring \
        pdo_mysql \
        xdebug \
        zip \
    && docker-php-source delete \
    && php -r "readfile('https://getcomposer.org/installer');" | php -- --install-dir=/usr/local/bin --filename=composer \
    && chmod +x /usr/local/bin/composer \
    && wget https://get.symfony.com/cli/installer -O - | bash && mv /root/.symfony/bin/symfony /usr/local/bin/symfony \
    && git config --global user.email $GIT_USERMAIL \
    && git config --global user.email $GIT_USERNAME

WORKDIR /app/
FROM php:8.1-fpm
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN apt-get update && apt-get install -y git iputils-ping net-tools

RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions @composer-2.6.6 xdebug-3.2.2 zip pdo_mysql intl

RUN docker-php-ext-enable xdebug

WORKDIR /app
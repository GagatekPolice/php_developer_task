FROM php:7.4-apache

WORKDIR /home

RUN a2enmod rewrite; \
    apt-get update; \
    apt-get install -y git unzip rsync wget vim libcurl4-openssl-dev; \
    docker-php-ext-install mysqli curl; \
    wget https://getcomposer.org/installer; \
    php installer --install-dir=/usr/local/bin/ --filename=composer;

COPY php.ini /usr/local/etc/php/conf.d/php.ini
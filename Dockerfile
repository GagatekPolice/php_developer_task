FROM php:7.4-apache

WORKDIR /home

RUN a2enmod rewrite; \
    apt-get update; \
    apt-get install -y git unzip rsync wget vim libcurl4-openssl-dev; \
    docker-php-ext-install mysqli curl; 

COPY php.ini /usr/local/etc/php/conf.d/php.ini

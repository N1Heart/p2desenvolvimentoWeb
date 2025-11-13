FROM php:8.2-apache

COPY . /var/www/html/
RUN docker-php-ext-install pdo pdo_mysql mysqli && docker-php-ext-enable mysqli

RUN a2enmod rewrite

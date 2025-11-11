# Use a imagem base do PHP com Apache
FROM php:8.2-apache

# Instala as extensões PDO e PDO_MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Habilita o mod_rewrite do Apache (bom para futuras URLs amigáveis)
RUN a2enmod rewrite

# (Opcional) Instala o Xdebug para debug
# RUN pecl install xdebug && docker-php-ext-enable xdebug
# RUN echo "xdebug.mode=develop,debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
# RUN echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
# RUN echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
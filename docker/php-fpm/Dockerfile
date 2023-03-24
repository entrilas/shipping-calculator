FROM php:8.1-fpm

RUN apt-get update -y && apt-get install -y openssl zip unzip
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN docker-php-ext-install pdo pdo_mysql

WORKDIR /var/www/html

COPY ./project /var/www/html/
#
RUN chown -R www-data:www-data /var/www/html/storage

RUN composer install

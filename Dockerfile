FROM php:7.2-apache
MAINTAINER Samuel Ackermann <saemi.ackermann@gmail.com>

RUN docker-php-ext-install pdo pdo_mysql
RUN docker-php-ext-install opcache

RUN a2enmod rewrite

COPY --chown=www-data:www-data . /var/www/html

ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf


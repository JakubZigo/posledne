FROM php:7.4-apache
COPY . /var/www/html
EXPOSE 80
RUN docker-php-ext-install pdo_mysql
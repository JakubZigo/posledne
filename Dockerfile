FROM php:7.4-apache
COPY . /var/www/html
EXPOSE 80
RUN docker-php-ext-install pdo_mysql
RUN apt-get update && apt-get upgrade -y \
    python3 \
    python3-pip
RUN python3 -m pip install --upgrade pip
RUN python3 -m pip install sympy
RUN python3 -m pip install antlr4-python3-runtime==4.11
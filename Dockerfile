FROM php:8.1-apache

RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN a2enmod rewrite

COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html


ARG MYSQLHOST
ARG MYSQLUSER
ARG MYSQLPASSWORD
ARG MYSQLDATABASE


ENV MYSQLHOST=${MYSQLHOST}
ENV MYSQLUSER=${MYSQLUSER}
ENV MYSQLPASSWORD=${MYSQLPASSWORD}
ENV MYSQLDATABASE=${MYSQLDATABASE}

EXPOSE 80

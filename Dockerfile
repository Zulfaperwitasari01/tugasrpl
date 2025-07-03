FROM php:8.1-apache

# Install ekstensi yang dibutuhkan
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Aktifkan mod_rewrite Apache
RUN a2enmod rewrite

# Salin semua file project ke dalam container
COPY . /var/www/html/

# Atur permission
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html


EXPOSE 80

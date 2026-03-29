FROM php:8.2-apache
# Install MySQL driver for PHP
RUN docker-php-ext-install pdo pdo_mysql
# Enable Apache Rewrite Module (Crucial for URL Rewriting)
RUN a2enmod rewrite
# Set the working directory to the public folder
WORKDIR /var/www/html
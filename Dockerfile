FROM php:8.2-apache
# Install MySQL driver for PHP
RUN docker-php-ext-install pdo pdo_mysql
# Enable Apache Rewrite Module (Crucial for URL Rewriting)
RUN a2enmod rewrite

# Serve the public directory as the web root.
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
	&& sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

WORKDIR /var/www/html
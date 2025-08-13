FROM php:8.2-apache

# Install the PHP extension needed to connect to MariaDB/MySQL
RUN docker-php-ext-install pdo_mysql

# Enable Apache's rewrite module
RUN a2enmod rewrite

# Copy our custom Apache configuration to tell it to use the /public folder
COPY vhost.conf /etc/apache2/sites-available/000-default.conf
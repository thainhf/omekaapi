FROM php:7.4-apache

# Enable needed Apache and PHP features and listen on port 9876 inside the container
RUN docker-php-ext-install mysqli pdo_mysql \
    && a2enmod rewrite \
    && sed -ri 's/Listen 80/Listen 9876/' /etc/apache2/ports.conf \
    && sed -ri 's/:80>/:9876>/' /etc/apache2/sites-available/000-default.conf

# Copy application code into the Apache document root
RUN mkdir -p /var/www/html/omekaapi
COPY --chown=www-data:www-data . /var/www/html/omekaapi/

# Ensure runtime directories exist and are writable by Apache
RUN mkdir -p /var/www/html/application/logs /var/www/html/uploads/tmp \
    && chmod -R 775 /var/www/html/application/logs /var/www/html/uploads /var/www/html/uploads/tmp

WORKDIR /var/www/html

EXPOSE 9876

CMD ["apache2-foreground"]

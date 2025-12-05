# Use official PHP 8.2 with Apache
FROM php:8.2-apache

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install required system packages
RUN apt-get update && apt-get install -y \
    git unzip libicu-dev libonig-dev libzip-dev zlib1g-dev libpng-dev libxml2-dev \
    && docker-php-ext-install intl pdo pdo_mysql zip opcache

# Configure Apache document root
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# Copy project files
COPY . /var/www/html/

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Allow Symfony Flex plugin and install PHP dependencies
RUN composer config --no-plugins allow-plugins.symfony/flex true && \
    composer install --no-dev --optimize-autoloader --ignore-platform-reqs


# Set permissions
RUN chown -R www-data:www-data /var/www/html/var
RUN chown -R www-data:www-data /var/www/html/public

EXPOSE 80
CMD ["apache2-foreground"]

# Use the official PHP image
# Use the official PHP Apache image
FROM php:8.2-apache

# Enable Apache mod_rewrite (optional but common for PHP apps)
RUN a2enmod rewrite

# Change Apache DocumentRoot to /public
RUN sed -i 's|/var/www/html|/var/www/html/public|g' \
    /etc/apache2/sites-available/000-default.conf

# Allow .htaccess overrides
RUN sed -i 's/AllowOverride None/AllowOverride All/g' \
    /etc/apache2/apache2.conf

RUN docker-php-ext-install mysqli

# Copy application files to Apache's web root
COPY . /var/www/html/

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html

# Expose HTTP port
EXPOSE 80

# Start Apache (default CMD provided by php:apache)
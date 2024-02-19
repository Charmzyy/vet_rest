# Use the PHP 8.2.12 CLI base image
FROM php:8.2.12-cli

# Update and install necessary dependencies
RUN apt-get update -y && apt-get install -y libmcrypt-dev libonig-dev unzip zlib1g-dev libzip-dev postgresql-client

# Install PostgreSQL client and required PHP extensions
RUN apt-get install -y libpq-dev \
    && docker-php-ext-install pdo_pgsql mbstring zip

# Copy Composer binary into PATH
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set PHP configurations
RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini \
    && sed -i -e "s/;extension=pdo_pgsql/extension=pdo_pgsql/" /usr/local/etc/php/php.ini

# Set Composer environment variable
ENV COMPOSER_ALLOW_SUPERUSER 1

# Set working directory

WORKDIR /app





COPY . /app

# Install Composer dependencies
RUN composer install --no-dev --no-interaction --prefer-dist

# Create symbolic link for storage
RUN php artisan storage:link

# Expose port 8001
EXPOSE 8001

# Command to run the application
CMD php artisan serve --host=0.0.0.0 --port=8001

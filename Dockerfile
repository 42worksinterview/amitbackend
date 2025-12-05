# -----------------------------------------
# Base PHP image with required extensions
# -----------------------------------------
FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git curl zip unzip \
        nginx supervisor \
        libzip-dev libonig-dev libpng-dev libxml2-dev \
        libfreetype6-dev libjpeg62-turbo-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql zip mbstring xml gd \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# -----------------------------------------
# Install Composer
# -----------------------------------------
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# -----------------------------------------
# Set working directory
# -----------------------------------------
WORKDIR /var/www/html

# Copy application
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Laravel permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# -----------------------------------------
# Configure Nginx
# -----------------------------------------
RUN rm /etc/nginx/sites-enabled/default
COPY .deploy/nginx.conf /etc/nginx/sites-enabled/laravel.conf

# -----------------------------------------
# Supervisor to run PHP-FPM + Nginx
# -----------------------------------------
COPY .deploy/supervisor.conf /etc/supervisor/conf.d/supervisor.conf

# Expose port
EXPOSE 80

# Start Supervisor
CMD ["/usr/bin/supervisord"]

# Use PHP version 8.2 as base image
FROM php:8.2-fpm

# Environment variables
ENV NODE_VERSION=18.19.0 \
    APP_DIR="/var/www/html" \
    APP_PORT="8000"

# Install system dependencies
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        gnupg \
        curl \
        git \
        zip \
        unzip \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev \
        locales \
        jpegoptim optipng pngquant gifsicle \
        vim \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql gd

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Node.js and npm
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs=${NODE_VERSION}-1nodesource1

# Install npm dependencies
WORKDIR $APP_DIR
COPY package*.json ./
RUN npm install

# Create a non-root user and switch to it
RUN addgroup -S appgroup && adduser -S -G appgroup appuser
RUN chown -R appuser:appgroup /var/www
USER appuser

# Install PHP dependencies using Composer
RUN composer install --no-dev --optimize-autoloader

# Install Node.js dependencies and build assets
RUN npm run prod

# Install Laravel Authentication Views and Spatie Role
RUN composer require laravel/ui \
    && php artisan ui bootstrap --auth \
    && composer require spatie/laravel-permission

# Optimize Laravel application
RUN php artisan optimize

# Expose the port used by the Laravel application
EXPOSE $APP_PORT

# Run the default command to start the web server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=$APP_PORT"]

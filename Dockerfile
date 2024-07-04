# Use PHP version 8.2 Alpine as base image
FROM php:8.2-fpm-alpine

# Environment variables
ENV NODE_VERSION=18.19.0 \
    APP_DIR="/var/www/html" \
    APP_PORT="8000"

# Install system dependencies
RUN apk update \
    && apk add --no-cache \
        gnupg \
        curl \
        git \
        zip \
        unzip \
        libpng-dev \
        libjpeg \
        freetype \
        nodejs \
        npm \
        jpegoptim \
        optipng \
        pngquant \
        gifsicle \
        vim \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql gd \
    && rm -rf /var/cache/apk/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Node.js and npm
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | sh - \
    && apk add --no-cache nodejs npm

# Install npm with custom prefix and permissions
ENV NPM_CONFIG_PREFIX=/home/appuser/.npm-global
ENV PATH=$PATH:/home/appuser/.npm-global/bin
RUN npm install -g npm --unsafe-perm=true

# Create a non-root user and group
RUN addgroup -S appgroup && adduser -S -D -G appgroup appuser

# Set the working directory
WORKDIR $APP_DIR

# Copy package.json and package-lock.json for npm install
COPY package*.json ./

# Install npm dependencies
RUN npm install --unsafe-perm

# Copy the rest of the application
COPY . .

# Set permissions for the application directory
RUN chown -R appuser:appgroup $APP_DIR \
    && chmod -R 755 $APP_DIR

# Switch to the non-root user
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
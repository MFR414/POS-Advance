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
    && docker-php-ext-install pdo pdo_mysql gd \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Node.js and npm
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs=${NODE_VERSION}-1nodesource1

# Install npm with custom prefix and permissions
ENV NPM_CONFIG_PREFIX=/home/appuser/.npm-global
ENV PATH=$PATH:/home/appuser/.npm-global/bin
RUN npm install -g npm --unsafe-perm=true

# Create a non-root user and group
RUN groupadd -r appgroup && useradd -r -g appgroup appuser

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

# Change the ownership of the npm cache directory
RUN mkdir -p /home/appuser/.npm && chown -R appuser:appgroup /home/appuser/.npm

# Switch to the non-root user
USER appuser

# Install PHP dependencies using Composer
RUN composer install --no-dev --optimize-autoloader

# Install Node.js dependencies and build assets
RUN npm install --unsafe-perm && npm run prod

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

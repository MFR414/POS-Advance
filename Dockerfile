# Use PHP version 8.2 as base image
FROM php:8.2

# Node.js version to be used
ENV \
    NODE_VERSION=18.19.0 \
    APP_DIR="/app" \
    APP_PORT="8000"

# Install dependencies for Node.js
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        gnupg \
        curl \
        git \
        zip \
        unzip \
    && curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y --no-install-recommends \
        nodejs=${NODE_VERSION}-1nodesource1 \
    && docker-php-ext-install pdo_mysql \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www/html

# Copy Laravel application files to container
COPY . .

# Switch to a non-root user (create one if needed)
RUN addgroup -g 1001 appuser && \
    adduser -D -u 1001 -G appuser appuser
USER appuser

# Install PHP dependencies using Composer
RUN composer install --no-dev --optimize-autoloader

# Install Node.js dependencies using npm and build assets
RUN npm install \
    && npm run prod

# Install Laravel Authentication Views and Spatie Role
RUN composer require laravel/ui \
    && php artisan ui bootstrap --auth \
    && composer require spatie/laravel-permission

# Optimize Laravel application
RUN php artisan optimize

# Expose the port used by the Laravel application
EXPOSE 8000

# Run the default command to start the web server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]

# Atau, jika kamu menggunakan web server seperti Nginx atau Apache, kamu bisa menggantinya dengan:
# CMD ["nginx", "-g", "daemon off;"]
# atau
# CMD ["apache2ctl", "-D", "FOREGROUND"]

#########################################

# # Gunakan image resmi PHP dengan versi 8.2
# FROM php:8.2-fpm

# # Set working directory
# WORKDIR /var/www

# # Install dependencies
# RUN apt-get update && apt-get install -y \
#     build-essential \
#     libpng-dev \
#     libjpeg-dev \
#     libfreetype6-dev \
#     locales \
#     zip \
#     jpegoptim optipng pngquant gifsicle \
#     vim \
#     unzip \
#     git \
#     curl \
#     && docker-php-ext-configure gd --with-freetype --with-jpeg \
#     && docker-php-ext-install pdo pdo_mysql gd

# # Install Composer
# COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# # Copy existing application directory contents
# COPY . /var/www

# # Copy existing application directory permissions
# COPY --chown=www-data:www-data . /var/www

# # Change the ownership of our applications
# RUN chown -R www-data:www-data /var/www

# #set the permissions
# RUN chown www-data:www-data storage
# RUN chmod 755 storage

# # Expose port 8002 and start php-fpm server
# EXPOSE 8002
# CMD ["php-fpm"]
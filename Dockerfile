# Stage 1: Build stage
FROM php:8.2-fpm AS build

# Install system dependencies for PHP extensions
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    git \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip pdo pdo_mysql

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set the working directory inside the container
WORKDIR /var/www

# Copy the composer.json and composer.lock files
COPY composer.json composer.lock ./

# Install PHP dependencies (production)
RUN composer install --no-dev --optimize-autoloader --prefer-dist

# Copy the application files
COPY . .

# Stage 2: Production image
FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www

# Install necessary system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    git \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip pdo pdo_mysql

# Copy Composer from the build stage
COPY --from=build /usr/local/bin/composer /usr/local/bin/composer

# Copy the application from the build stage
COPY --from=build /var/www /var/www

# Copy .env.production to .env (Laravel will automatically use this)
COPY .env.production .env

# Set permissions (Laravel storage and cache)
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Run migrations (optional, but useful in production)
RUN php artisan migrate --force

# Expose the port for the PHP-FPM service
EXPOSE 9000

# Set the command to run PHP-FPM and migrate if needed
CMD ["sh", "-c", "php artisan migrate --force && php-fpm"]

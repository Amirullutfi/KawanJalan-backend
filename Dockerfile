FROM php:8.2-cli-alpine

# Install system dependencies
RUN apk add --no-cache \
    postgresql-dev \
    nodejs \
    npm \
    git \
    zip \
    unzip

# Install PHP extensions required for PostgreSQL
RUN docker-php-ext-install pdo pdo_pgsql

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy the application code
COPY . /app

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Install NPM dependencies and build frontend assets (jika diperlukan)
RUN npm install && npm run build

# Change permissions for Laravel cache and storage
RUN chown -R www-data:www-data /app \
    && chmod -R 775 /app/storage \
    && chmod -R 775 /app/bootstrap/cache

# Start the Laravel application using the port Render gives us
CMD php artisan serve --host=0.0.0.0 --port=$PORT

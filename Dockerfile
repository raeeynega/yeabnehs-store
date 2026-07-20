FROM node:20 AS frontend
WORKDIR /app
COPY package.json package-lock.json* ./
RUN npm install
COPY . .
RUN npm run build

FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libxml2-dev libpq-dev libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/yeabnehs-store

# Copy composer files first for caching
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

# Copy the rest of the app
COPY . .

# Copy built frontend assets from node stage
COPY --from=frontend /app/public/build /var/www/yeabnehs-store/public/build

# Generate autoloader and optimize
RUN composer dump-autoload --optimize \
    && composer install --no-dev --optimize-autoloader

# Storage permissions
RUN mkdir -p storage/framework/{cache,sessions,testing,views} \
    && mkdir -p storage/logs \
    && mkdir -p bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 8000

CMD php artisan migrate --force; php artisan serve --host=0.0.0.0 --port=8000

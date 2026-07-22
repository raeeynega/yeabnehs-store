# --- Stage 1: Build frontend ---
FROM node:20 AS frontend
WORKDIR /app
COPY package.json package-lock.json* ./
RUN npm install
COPY . .
RUN npm run build

# --- Stage 2: Production with Nginx + PHP-FPM ---
FROM php:8.2-fpm-bookworm

# Install Nginx + system deps
RUN apt-get update && apt-get install -y --no-install-recommends \
    nginx \
    gettext-base \
    git curl zip unzip libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libxml2-dev libpq-dev libzip-dev supervisor \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath gd zip opcache \
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

# --- Nginx config (template — generated at runtime with $PORT) ---
RUN rm -f /etc/nginx/sites-enabled/default
COPY nginx/default.conf.template /var/www/yeabnehs-store/nginx/default.conf.template

# --- PHP-FPM pool tuning (production) ---
RUN echo "pm = dynamic" >> /usr/local/etc/php-fpm.d/www.conf \
    && echo "pm.max_children = 10" >> /usr/local/etc/php-fpm.d/www.conf \
    && echo "pm.start_servers = 3" >> /usr/local/etc/php-fpm.d/www.conf \
    && echo "pm.min_spare_servers = 2" >> /usr/local/etc/php-fpm.d/www.conf \
    && echo "pm.max_spare_servers = 5" >> /usr/local/etc/php-fpm.d/www.conf \
    && echo "pm.max_requests = 500" >> /usr/local/etc/php-fpm.d/www.conf \
    && echo "request_terminate_timeout = 60s" >> /usr/local/etc/php-fpm.d/www.conf

# --- OPcache (production) ---
RUN echo "opcache.enable=1" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.memory_consumption=128" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.interned_strings_buffer=8" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.max_accelerated_files=10000" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.revalidate_freq=60" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.save_comments=1" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.validate_timestamps=0" >> /usr/local/etc/php/conf.d/opcache.ini

# --- Supervisor: run PHP-FPM + Nginx ---
COPY nginx/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Make entrypoint executable
RUN chmod +x entrypoint.sh

# Create storage directories
RUN mkdir -p storage/framework/{cache,sessions,testing,views} \
    && mkdir -p storage/logs \
    && mkdir -p bootstrap/cache \
    && mkdir -p /run/nginx \
    && chmod -R 775 storage bootstrap/cache

# Create audit log directory (immutable append-only)
RUN mkdir -p storage/audit \
    && chmod 1733 storage/audit

EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

FROM php:8.3-fpm AS base

# Install dependencies
RUN apt-get update && apt-get install -y \
    git curl zip unzip nodejs npm \
    libpng-dev libjpeg-dev libfreetype6-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

FROM base AS dev

COPY . .

# Install Composer dependencies (include dev deps)
RUN composer install

# Set permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Environment variable to disable opcache optimization
ENV OPCACHE_VALIDATE_TIMESTAMPS=1 \
    OPCACHE_REVALIDATE_FREQ=0 \
    CHOKIDAR_USEPOLLING=true

CMD ["php-fpm"]
EXPOSE 9000

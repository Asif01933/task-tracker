FROM php:8.2-fpm

# System deps
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
    libonig-dev libxml2-dev nano \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd \
    && rm -rf /var/lib/apt/lists/*

# Opcache (good defaults for dev)
RUN docker-php-ext-install opcache

# Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Add a user to ensure we don't run as root
RUN groupadd -g 1000 laravel && useradd -u 1000 -g laravel -s /bin/bash laravel
USER laravel
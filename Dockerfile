FROM php:8.2-cli

WORKDIR /var/www/Jukebox

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock ./
RUN composer install --optimize-autoloader

COPY . .

# RUN chmod +x bin/jukebox.php

CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]

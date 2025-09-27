FROM php:8.3-fpm-alpine

RUN apk add --no-cache \
    bash \
    git \
    zip \
    unzip \
    libpq \
    postgresql-dev \
    libpng-dev \
    oniguruma-dev \
    curl \
    && docker-php-ext-install pdo pdo_pgsql pgsql mbstring exif pcntl bcmath gd

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
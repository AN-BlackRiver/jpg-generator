FROM php:8.2-fpm

RUN docker-php-ext-install pdo pdo_mysql

RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

RUN apt-get install -y \
    libzip-dev \
    zip \
    && docker-php-ext-install zip

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN mkdir -p /var/www/html/cache && chown -R www-data:www-data /var/www/html/cache

RUN groupmod -g 1000 www-data && usermod -u 1000 -g 1000 www-data
FROM php:8.2-fpm

# Установка зависимостей и расширений PHP в одном RUN
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libzip-dev \
    zip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql gd zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Настройка пользователя
RUN groupmod -g 1000 www-data && usermod -u 1000 -g 1000 www-data
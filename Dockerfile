FROM php:8.4-cli

# Установка системных зависимостей
RUN apt-get update && apt-get install -y \
    libpq-dev \
    unzip \
    zip \
    curl \
    git \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    && docker-php-ext-install pdo pdo_pgsql mbstring zip

# Установка Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Установка рабочего каталога
WORKDIR /var/www/html

# Устанавливаем зависимости Laravel
COPY . .

RUN composer install --no-dev --optimize-autoloader

# Запуск Laravel
CMD php artisan serve --host=0.0.0.0 --port=8000

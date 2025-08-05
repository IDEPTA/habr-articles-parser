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
    libmemcached-dev \
    zlib1g-dev \
    libssl-dev \
    libevent-dev \
    && docker-php-ext-install pdo pdo_pgsql mbstring zip \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && pecl install memcached \
    && docker-php-ext-enable memcached

# Установка Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Установка рабочего каталога
WORKDIR /var/www/html

# Копируем проект полностью
COPY . .

# Устанавливаем зависимости Laravel
RUN composer install --no-dev --optimize-autoloader

# Открываем порт
EXPOSE 8000

# Запуск Laravel
CMD php artisan serve --host=0.0.0.0 --port=8000

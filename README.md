# Laravel Article Search (Habr RSS + Elasticsearch)

Этот проект — парсер статей с Habr, сохраняющий данные в базу и предоставляющий полнотекстовый поиск через Elasticsearch с помощью Laravel Scout.

## 📦 Стек

- PHP 8.3
- Laravel 12
- Laravel Scout
- Elasticsearch (через `matchish/laravel-scout-elasticsearch`)
- Docker
- MySQL / PostgreSQL

## 🧠 Установка

1. Клонируйте репозиторий:

```bash
git clone https://github.com/yourname/laravel-article-search.git
cd laravel-article-search
```

2. Запускаем докер:

```bash
git docker-compose up -d
```

3. В .env проекта добавляем

```php
SCOUT_DRIVER=Matchish\ScoutElasticSearch\Engines\ElasticSearchEngine
ELASTICSEARCH_HOST=localhost:9200
```

4. В laravel-app контейнере выполняем:

```bash
git composer install
php artisan migrate
php artisan vendor:publish --provider="Laravel\Scout\ScoutServiceProvider"
```
5. Регистрируем Elasticsearch пакет в bootstrap/providers.php:

```php
return [
    //Другие зависимости
    \Matchish\ScoutElasticSearch\ElasticSearchServiceProvider::class
];
```

6. Парсим данные с хабра и заполняем бд:

```bash
php artisan rss:habr
```

7. Если нужно, то выполняем, для импорта записей бд в Elasticsearch:

```bash
php artisan scout:import
```

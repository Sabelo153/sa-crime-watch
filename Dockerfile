FROM php:8.3-cli-bookworm
RUN apt-get update && apt-get install -y --no-install-recommends unzip libpng-dev libjpeg62-turbo-dev libwebp-dev libsqlite3-dev libonig-dev libxml2-dev && docker-php-ext-configure gd --with-jpeg --with-webp && docker-php-ext-install gd pdo_mysql pdo_sqlite mbstring dom && rm -rf /var/lib/apt/lists/*
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html
COPY . .
RUN composer install --no-interaction --prefer-dist --no-scripts && php artisan package:discover
RUN printf 'upload_max_filesize=5M\npost_max_size=24M\nmemory_limit=256M\n' > /usr/local/etc/php/conf.d/watch.ini
EXPOSE 8000
CMD ["php","artisan","serve","--host=0.0.0.0","--port=8000"]

FROM php:8.2-cli

WORKDIR /var/www/app

RUN apt-get update \
    && apt-get install -y --no-install-recommends git unzip libzip-dev libonig-dev libcurl4-openssl-dev \
    && docker-php-ext-install curl mbstring pdo_mysql zip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock ./
RUN composer install --no-interaction --no-dev --prefer-dist --optimize-autoloader

COPY . .

EXPOSE 8000

CMD ["sh", "-c", "php -S 0.0.0.0:${PORT:-8000} -t public"]

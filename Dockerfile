
FROM php:8.3-fpm-alpine


RUN apk add --no-cache \
    git \
    curl \
    libxml2-dev \
    zip \
    unzip \
    oniguruma-dev \
    mysql-client \
    $PHPIZE_DEPS

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath opcache \
    && docker-php-ext-enable opcache

RUN apk add --no-cache pcre-dev \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del pcre-dev $PHPIZE_DEPS


COPY --from=composer:latest /usr/bin/composer /usr/bin/composer


WORKDIR /var/www/html

COPY composer.json composer.lock ./


RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist \
    && composer clear-cache


COPY . .


RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

COPY docker/php/prod/php.ini /usr/local/etc/php/conf.d/custom.ini
COPY docker/php/prod/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf


EXPOSE 9000


CMD ["php-fpm"]



FROM php:8.1-apache
RUN apt-get update && apt-get install -y \
    libpng-dev \
    zlib1g-dev \
    libxml2-dev \
    libzip-dev \
    libonig-dev \
    zip \
    curl \
    unzip

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd
COPY . /var/www/html
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf
COPY --from=composer /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER 1
RUN composer update
RUN composer install
RUN chown -R www-data:www-data \
    /var/www/html/storage \
    /var/www/html/bootstrap/cache

RUN a2enmod rewrite
# WORKDIR /var/www/html
# RUN php artisan migrate

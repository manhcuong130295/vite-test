FROM php:8.1-fpm

RUN apt-get update

RUN apt-get install -y libzip-dev zip unzip libpng-dev libjpeg-dev zlib1g-dev procps vim

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install php ext
RUN docker-php-ext-configure gd  --with-jpeg
RUN docker-php-ext-install pdo pdo_mysql zip exif gd

WORKDIR /var/www/html
COPY . /var/www/html

RUN rm -f composer.lock
RUN rm -rf ./storage/logs/laravel.*
RUN chmod -R 777 storage

RUN composer install

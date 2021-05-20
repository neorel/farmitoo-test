FROM php:8-fpm-alpine

RUN apk --no-cache update

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && php composer-setup.php && php -r "unlink('composer-setup.php');" && mv composer.phar /usr/local/bin/composer

COPY . /var/www/html

WORKDIR /var/www/html

RUN composer install
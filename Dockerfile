FROM php:7.2-apache
RUN apt-get update -y && apt-get install -y openssl zip unzip git netcat vim nano
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install sockets
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
COPY . /var/www/html
COPY vhost.conf /etc/apache2/sites-available/000-default.conf
# COPY ./public/.htaccess /var/www/html/.htaccess
WORKDIR /var/www/html
RUN composer install \
    --ignore-platform-reqs \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist
# RUN mv .env .env.tmp
RUN mv .env.dist .env
# RUN php artisan key:generate
RUN chmod 777 -R var/
RUN mkdir .data/mysql
RUN chmod 777 -R .data/
RUN chown -R www-data:www-data var
RUN chown -R www-data:www-data var/cache
RUN a2enmod rewrite
RUN service apache2 restart

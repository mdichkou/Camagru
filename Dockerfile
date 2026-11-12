FROM php:8.2.35-apache-bookworm

RUN apt-get update \
    && apt-get install -y --no-install-recommends libpng-dev libjpeg62-turbo-dev msmtp \
    && docker-php-ext-configure gd --with-jpeg \
    && docker-php-ext-install gd pdo_mysql \
    && rm -rf /var/lib/apt/lists/*

COPY docker/msmtprc /etc/msmtprc
COPY docker/php.ini /usr/local/etc/php/conf.d/camagru.ini
COPY docker/apache.conf /etc/apache2/conf-enabled/camagru.conf
COPY . /var/www/html/
RUN mkdir -p /var/www/html/uploads \
    && chown www-data:www-data /var/www/html/uploads

ENTRYPOINT ["sh", "/var/www/html/docker/entrypoint.sh"]
CMD ["apache2-foreground"]

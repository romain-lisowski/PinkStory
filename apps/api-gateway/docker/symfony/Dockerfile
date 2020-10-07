FROM php:7.4-fpm

# install deps
RUN apt-get update && apt-get install -y \
        libicu-dev \
        libzip-dev \
        unzip \
        libpq-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl \
    && docker-php-ext-install zip \
    && docker-php-ext-install pdo pdo_pgsql \
    && docker-php-ext-enable opcache \

    # php cs fixer
    && curl -L https://cs.symfony.com/download/php-cs-fixer-v2.phar -o php-cs-fixer\
    && chmod a+x php-cs-fixer \
    && mv php-cs-fixer /usr/local/bin/php-cs-fixer

# php ini configuration
COPY ./conf.d/php.ini /usr/local/etc/php/conf.d/docker-php.ini

# install composer from docker hub
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
EXPOSE 9000
FROM php:8.2-fpm-alpine

# Prepare directories
ENV DATA_ROOT=/data
ENV PATH=${DATA_ROOT}/bin:${DATA_ROOT}/vendor/bin:${PATH}

WORKDIR ${DATA_ROOT}

COPY .. ${DATA_ROOT}

# Install dependencies
RUN apk update \
 && apk upgrade --available \
 && apk add --virtual internalpack curl libpq-dev

# Composer install
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

# PHP extenstions
RUN docker-php-ext-install pdo_pgsql

# Run composer install
ENV COMPOSER_ALLOW_SUPERUSER=1
COPY ../composer.json composer.lock ./
RUN /usr/bin/composer install --no-interaction --no-progress

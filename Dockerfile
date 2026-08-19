# syntax=docker/dockerfile:1

##############################
# Stage 1: PHP dependencies
##############################
FROM composer:2 AS vendor

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-scripts \
    --no-autoloader \
    --ignore-platform-reqs \
    --prefer-dist

COPY . .
RUN composer dump-autoload --optimize --no-dev

##############################
# Stage 2: Frontend assets
##############################
FROM node:20-alpine AS assets

WORKDIR /app

COPY package.json package-lock.json* ./
RUN npm install

COPY resources ./resources
COPY vite.config.js ./
RUN npm run build

##############################
# Stage 3: Runtime image
##############################
FROM php:8.2-apache AS runtime

# System packages + PHP extensions required by the app
# (mpdf/dompdf/maatwebsite-excel need gd, mbstring, zip, dom; pdo_mysql for MySQL)
RUN apt-get update && apt-get install -y --no-install-recommends \
        libzip-dev \
        libpng-dev \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
        libonig-dev \
        unzip \
        git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        pdo_mysql \
        mbstring \
        exif \
        bcmath \
        gd \
        zip \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

# Point Apache's document root at Laravel's public/ directory
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e "s!/var/www/html!${APACHE_DOCUMENT_ROOT}!g" /etc/apache2/sites-available/*.conf \
    && sed -ri -e "s!/var/www/!${APACHE_DOCUMENT_ROOT}!g" /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

WORKDIR /var/www/html

COPY --from=vendor /app ./
COPY --from=assets /app/public/build ./public/build

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Force mpm_prefork as the only possible MPM. Deleting just the mods-enabled
# symlinks wasn't enough — something (a dpkg trigger) kept re-creating them
# later, causing "AH00534: More than one MPM loaded" on Railway even though
# the same fix passed locally. Deleting the source files in mods-available
# too means there is nothing left for anything to re-enable.
RUN rm -f /etc/apache2/mods-available/mpm_event.load /etc/apache2/mods-available/mpm_event.conf \
          /etc/apache2/mods-available/mpm_worker.load /etc/apache2/mods-available/mpm_worker.conf \
          /etc/apache2/mods-enabled/mpm_event.load /etc/apache2/mods-enabled/mpm_event.conf \
          /etc/apache2/mods-enabled/mpm_worker.load /etc/apache2/mods-enabled/mpm_worker.conf \
    && ln -sf /etc/apache2/mods-available/mpm_prefork.load /etc/apache2/mods-enabled/mpm_prefork.load \
    && ln -sf /etc/apache2/mods-available/mpm_prefork.conf /etc/apache2/mods-enabled/mpm_prefork.conf \
    && apache2ctl -M 2>&1 | grep -c mpm_ | grep -qx 1

EXPOSE 80

ENTRYPOINT ["entrypoint.sh"]
CMD ["apache2-foreground"]

FROM php:7.0-fpm

RUN apt-get update && apt-get install -y \
        curl \
        gnupg \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libxml2-dev \
        libpng-dev \
    && docker-php-ext-install -j$(nproc) gd xml mbstring

RUN curl -sS https://getcomposer.org/installer \
    | php -- --install-dir=/usr/local/bin --filename=composer
RUN curl -sL https://deb.nodesource.com/setup_10.x | bash - && \
    apt-get install -y nodejs

WORKDIR /usr/src/author-reports

COPY . ./
RUN chown -R www-data:root storage/
RUN composer install
RUN npm install

CMD ["php-fpm"]

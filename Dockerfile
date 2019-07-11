FROM php:7.2-fpm

COPY src/ /app

RUN mkdir -p /opt/uploads
COPY /conf/upload.ini /usr/local/etc/php/conf.d/upload.ini

RUN apt update && apt install -y \
        procps \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
    && docker-php-ext-install -j$(nproc) iconv \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd


WORKDIR /app
CMD ["php", "-S", "0.0.0.0:5000"]
CMD ["tail", "-f", "/dev/null"]

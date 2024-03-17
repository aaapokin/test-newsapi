FROM php:8.4-fpm-alpine3.19

RUN apk add --update --no-cache --virtual .build-dependencies $PHPIZE_DEPS \
    && apk add --no-cache libpng-dev libjpeg-turbo-dev freetype-dev linux-headers shadow oniguruma-dev \
    postgresql-dev \
    && pecl install apcu \
    && docker-php-ext-enable apcu \
    && docker-php-ext-install pdo_mysql sockets mbstring exif pcntl bcmath \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && docker-php-ext-install pgsql pdo_pgsql \
    && pecl clear-cache \
    && apk del .build-dependencies



# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer


RUN printf "post_max_size=20M\nupload_max_filesize=20M\nmemory_limit=1024M" > /usr/local/etc/php/conf.d/file-uploads.ini

WORKDIR /application

RUN apk add --no-cache bash

ARG USER_ID=1000
ARG GROUP_ID=1000

RUN usermod -u ${USER_ID} www-data \
    && groupmod -g ${GROUP_ID} www-data

COPY ./entrypoint-local.sh /docker-entrypoint.d/custom-entrypoint.sh
RUN chmod +x /docker-entrypoint.d/custom-entrypoint.sh

EXPOSE 9000
#ENTRYPOINT ["/docker-entrypoint.d/custom-entrypoint.sh"]
#CMD ["php-fpm"]

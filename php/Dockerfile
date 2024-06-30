FROM php:8.3-fpm-alpine3.20

# curl, openssl, tar mbstring is already install from php base image.
# Keep all RUN instructions in a single line so we can reduce image 
# size removing unnecessary build packages.

RUN apk add --no-cache \
    git openssh openrc libpng libzip icu libxml2 supervisor && \
    \
    apk add --no-cache --virtual .build-deps curl \
    libpng-dev libzip-dev libxml2-dev icu-dev linux-headers $PHPIZE_DEPS &&\
    \
    docker-php-ext-configure gd --enable-gd && docker-php-ext-install gd &&\
    docker-php-ext-configure intl && docker-php-ext-install intl && \
    docker-php-ext-install pdo pdo_mysql zip xml bcmath && \
    pecl install redis xdebug && \
    docker-php-ext-enable redis xdebug && \
    \
    apk del --purge .build-deps

COPY --from=composer /usr/bin/composer /usr/bin/composer

# Install Node.js and npm
RUN apk add --update nodejs npm yarn

COPY ./application /var/www/html/currency-exchange

ARG WORKDIR
WORKDIR $WORKDIR

ARG APP_VERSION
ENV APP_VERSION=$APP_VERSION

ARG APP_VERSION_DATE
ENV APP_VERSION_DATE=$APP_VERSION_DATE

COPY php/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

CMD ["/usr/local/bin/start.sh"]
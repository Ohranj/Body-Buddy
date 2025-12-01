FROM php:8.4-fpm


ARG user=demo
ARG work_dir=/var/www


WORKDIR ${work_dir}


COPY . ${work_dir}
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer


# composer global about for path
ENV PATH="$PATH:/home/${user}/.config/composer/vendor/bin"


RUN apt-get update && apt-get upgrade -y && apt-get install -y \
    git \
    zlib1g-dev \
    libzip-dev \
    unzip \
    vim \
    sqlite3 \
    libpng-dev \
    libfreetype-dev \
	libjpeg62-turbo-dev \
    libmagickwand-dev


RUN curl -sL https://deb.nodesource.com/setup_22.x -o /tmp/nodesource_setup.sh
RUN bash /tmp/nodesource_setup.sh
RUN apt-get install -y nodejs

RUN pecl install imagick

RUN docker-php-ext-install gd
RUN docker-php-ext-install zip
RUN docker-php-ext-install pdo
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install pcntl

RUN docker-php-ext-enable imagick
RUN docker-php-ext-enable pcntl

ADD ./docker-configs/php/custom-php.ini /usr/local/etc/php/conf.d/custom-php.ini

run mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"


RUN useradd -ms /bin/bash ${user}

USER ${user}


# RUN composer global require laravel/installer


#docker compose exec app bash
#laravel new example-app


#php -i | grep memory_limit
FROM php:7.4-fpm
MAINTAINER Alexandros Koutroulis icyd3mon@gmail.com

RUN apt-get update && apt-get install -y apt-utils git unzip build-essential make gnupg

RUN docker-php-ext-install -j$(nproc) pdo pdo_mysql

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN curl --silent --show-error https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer

WORKDIR /var/www

RUN curl -sL https://deb.nodesource.com/setup_14.x  | bash -
RUN apt-get -y install nodejs

CMD php-fpm
EXPOSE 9000

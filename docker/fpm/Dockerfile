FROM php:7.4-fpm
MAINTAINER Alexandros Koutroulis icyd3mon@gmail.com

RUN apt-get update && apt-get install -y apt-utils git unzip

RUN docker-php-ext-install -j$(nproc) pdo pdo_mysql

RUN curl --silent --show-error https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer

WORKDIR /var/www
CMD php-fpm
EXPOSE 9000

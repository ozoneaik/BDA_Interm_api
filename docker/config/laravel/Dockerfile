FROM php:8.1.11-fpm-alpine

#update timezone
RUN ln -sf /usr/share/zoneinfo/Asia/Bangkok /etc/localtime

RUN apk update
RUN apk add --no-cache \
    curl git zip libzip-dev \
    libpq libpq-dev \
    libjpeg-turbo-dev libpng libpng-dev libxml2-dev \
    supervisor

RUN docker-php-ext-configure gd --with-jpeg
RUN docker-php-ext-install pdo pdo_pgsql gd zip
RUN docker-php-ext-install exif soap

# ImageMagick:
RUN apk add --update --no-cache autoconf g++ imagemagick imagemagick-dev libtool make pcre-dev
RUN printf "\n" | pecl install imagick
RUN echo 'extension=imagick.so' > /usr/local/etc/php/conf.d/ext-imagick.ini
RUN docker-php-ext-enable imagick
RUN apk del autoconf libtool make pcre-dev

#RUN pecl install -o -f redis \
#    &&  rm -rf /tmp/pear \
#    &&  docker-php-ext-enable redis
#####################################
# Supervisor for queue:
#####################################

COPY supervisord.conf /etc/supervisord.conf

#####################################
# Composer:
#####################################
COPY --from=composer /usr/bin/composer /usr/bin/composer

#RUN usermod -u 1000 www-data
WORKDIR /var/www

#####################################
# custom start command
#####################################
COPY start.sh /usr/local/bin/start
RUN chmod u+x /usr/local/bin/start

#####################################
# Other php ini config:
#####################################
COPY custom_config.ini /usr/local/etc/php/conf.d/custom_config.ini

EXPOSE 9000

#####################################
# CRON
##w###################################
COPY cron  /etc/crontabs/root

CMD [ "sh", "/usr/local/bin/start" ]

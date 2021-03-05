FROM php:7.4-fpm

RUN apt-get update \
    && apt-get upgrade -y \
    && export DEBIAN_FRONTEND=noninteractive \
    && apt-get install -yy \
        curl \
        zip \
        apt-utils \
        pkg-config \
        libssl-dev \
        libcurl4-gnutls-dev \
        libxml2-dev \
        libicu-dev \
        libmcrypt-dev \
        iproute2 \
    && apt-get clean -yy

RUN docker-php-ext-install \
        opcache \
        pdo_mysql \
        pcntl \
        intl \
        soap \
        mysqli

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN php -r "copy('https://getcomposer.org/installer', '/tmp/composer-setup.php');"
RUN php -r "if (hash_file('SHA384', '/tmp/composer-setup.php') === '544e09ee996cdf60ece3804abc52599c22b1f40f4323403c44d44fdfdd586475ca9813a858088ffbc1f233e9b180f061') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php /tmp/composer-setup.php
RUN php -r "unlink('/tmp/composer-setup.php');"
RUN cp -rv composer.phar /usr/bin/composer
RUN chmod +x /usr/bin/composer
RUN apt install git -y

RUN echo "[Xdebug]\n" \
          "xdebug.remote_enable=1\n" \
          "xdebug.remote_port=9032\n" \
          "xdebug.remote_autostart=1\n" \
          "xdebug.idekey=PHPSTORM_XDEBUG\n" \
          "xdebug.max_nesting_level=1000\n" \
#          "xdebug.remote_host="`/sbin/ip route list match 0/0 |awk '{ print $3 }'` > /usr/local/etc/php/conf.d/xdebug.ini
          "xdebug.remote_host=host.docker.internal" > /usr/local/etc/php/conf.d/xdebug.ini

RUN echo "date.timezone=UTC" > /usr/local/etc/php/conf.d/timezone_sao_paulo.ini

RUN echo "upload_max_filesize = 10MB\n" \
         "post_max_size = 10MB" > /usr/local/etc/php/conf.d/upload_limits.ini

EXPOSE 9000
WORKDIR /app
CMD ["php-fpm"]

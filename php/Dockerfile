FROM php:7.2-fpm

# UPDATE LOCAL DATETIME
RUN rm /etc/localtime
RUN ln -s /usr/share/zoneinfo/Europe/Paris /etc/localtime

# UPDATE & UPGRADE SERVER
RUN echo "deb http://ftp.fr.debian.org/debian jessie-backports main" >> /etc/apt/sources.list
RUN echo "deb http://ftp.fr.debian.org/debian jessie main non-free" >> /etc/apt/sources.list
RUN apt-get update && apt-get -y upgrade

# INSTALL DEPENDENCY
RUN apt-get -my install git cron zip unzip libzip-dev libpng-dev gnupg openssl libc-client-dev libkrb5-dev

# INSTALL PHP EXTENSION
RUN docker-php-ext-install zip gd opcache bcmath pdo_mysql exif

RUN pecl install apcu
RUN echo "extension=apcu.so" > /usr/local/etc/php/conf.d/apcu.ini

RUN curl -S -o /tmp/icu.tar.gz -L "https://downloads.sourceforge.net/project/icu/ICU4C/57.1/icu4c-57_1-src.tgz" && tar -zxf /tmp/icu.tar.gz -C /tmp && cd /tmp/icu/source && ./configure --prefix=/usr/local && make && make install

RUN docker-php-ext-configure intl --with-icu-dir=/usr/local && docker-php-ext-install intl

RUN docker-php-ext-configure imap --with-kerberos --with-imap-ssl && docker-php-ext-install imap

RUN docker-php-source extract \
    && curl -L -o /tmp/redis.tar.gz https://github.com/phpredis/phpredis/archive/4.0.0.tar.gz \
    && tar xfz /tmp/redis.tar.gz \
    && rm -r /tmp/redis.tar.gz \
    && mv phpredis-4.0.0 /usr/src/php/ext/redis \
    && docker-php-ext-install redis \
    && docker-php-source delete

# INSTALL COMPOSER
RUN php -r "readfile('https://getcomposer.org/installer');" | php -- --install-dir=/usr/local/bin --filename=composer; chmod +x /usr/local/bin/composer

# INSTALL CRONTAB
ADD crontab /etc/cron.d/crontab
RUN chmod 0644 /etc/cron.d/crontab

# make pam_loginuid.so optional for cron
# see https://github.com/docker/docker/issues/5663#issuecomment-42550548
RUN sed --regexp-extended --in-place \
    's/^session\s+required\s+pam_loginuid.so$/session optional pam_loginuid.so/' \
    /etc/pam.d/cron

# COPY PHP CONFIGURATION FILES
ADD php.dev.ini /usr/local/etc/php/php.dev.ini
ADD php.prod.ini /usr/local/etc/php/php.prod.ini

# INIT THE PROJECT
COPY ./entrypoint.sh /entrypoint.sh
ENTRYPOINT ["/entrypoint.sh", "docker-php-entrypoint"]

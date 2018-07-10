#!/bin/bash
set -e

# remove write permission for (g)roup and (o)ther (required by cron)
chmod -R go-w /etc/cron.d

# update default values of PAM environment variables (used by CRON scripts)
env | while read -r LINE; do
    IFS="=" read VAR VAL <<< ${LINE}
    sed --in-place "/^${VAR}[[:blank:]=]/d" /etc/security/pam_env.conf || true
    echo "${VAR} DEFAULT=\"${VAL}\"" >> /etc/security/pam_env.conf
done

# Start cron
service cron start
crontab -uroot /etc/cron.d/crontab

# Install xdebug to run unit test and have code coverage options
if [ "$ENV" == "dev" ]; then
    cp -f /usr/local/etc/php/php.dev.ini /usr/local/etc/php/conf.d/php.ini
    pecl install -f xdebug-2.6.1
    XDEBUG_PATH=`find / -name "xdebug.so"`
    echo "zend_extension=$XDEBUG_PATH" > /usr/local/etc/php/conf.d/ext-xdebug.ini
else
    cp -f /usr/local/etc/php/php.prod.ini /usr/local/etc/php/conf.d/php.ini
fi

# first arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
	set -- php-fpm "$@"
fi

exec "$@"

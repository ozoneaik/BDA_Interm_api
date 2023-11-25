#!/bin/bash

# Defaults to an app server
role=${CONTAINER_ROLE:-app}
echo "start role ${role}"
if [ "$role" = "queue" ]; then
    echo "START QUEUE"
    /usr/bin/supervisord -c /etc/supervisord.conf
elif [ "$role" = "app" ]; then
    composer install --no-dev
    echo "START APP"
    exec php-fpm
elif [ "$role" = "schedule" ]; then
    echo "START SCHEDULE"
    sed -i 's/www-data:\/sbin\/nologin/www-data:\/bin\/ash/g' /etc/passwd
    crond -f
else
    echo "Could not match the container role...."
    exit 1
fi

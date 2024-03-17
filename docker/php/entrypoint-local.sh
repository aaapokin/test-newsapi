#!/bin/bash
echo -e  "\033[32m  !!!!!!!!!!!!!!!!!!!!!!!!!! start entrypoint-local.sh !!!!!!!!!!!!!!!!!!!!!!!!!!!! \033[0m"

chown -R www-data:www-data /application
chmod -R 755 /application
cd /application && composer install
cd /application && php artisan migrate
#cd /application && php artisan rmq:declare-package-exchanges

echo -e  "\033[32m  !!!!!!!!!!!!!!!!!!!!!!!!!! end entrypoint-local.sh !!!!!!!!!!!!!!!!!!!!!!!!!!!! \033[0m"

exec "$@"


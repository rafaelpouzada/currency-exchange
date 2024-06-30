#!/bin/sh

IP=$(curl -s icanhazip.com)
echo $IP > /var/www/html/currency-exchange/public-ip.txt

exec php-fpm

cd /var/www/html/currency-exchange
yarn install
yarn run development --watch

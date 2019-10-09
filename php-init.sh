#!/bin/bash

cp common/config/main-local.php.dist common/config/main-local.php
sed -i -- "s~{NAME}~${MYSQL_DATABASE}~g" common/config/main-local.php
sed -i -- "s~{USER}~${MYSQL_USER}~g" common/config/main-local.php
sed -i -- "s~{PASSWORD}~${MYSQL_PASSWORD}~g" common/config/main-local.php
sed -i -- "s~localhost~mariadb~g" common/config/main-local.php
#cp console/config/main-local.php.dist console/config/main-local.php
#sed -i -- "s~{DOMAIN}~http://${DOMAIN}~g" console/config/main-local.php
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
php -r "unlink('composer-setup.php');"
php composer.phar install
php composer.phar update
php init --env=Development --overwrite=No

chown -R 500:500 /var/www/html/
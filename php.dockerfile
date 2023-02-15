from php:fpm-alpine


run wget https://getcomposer.org/installer && php ./installer && mv composer.phar /usr/local/bin/composer && rm ./installer
run apk add libpq-dev libpq && docker-php-ext-install pdo_pgsql && apk del libpq-dev
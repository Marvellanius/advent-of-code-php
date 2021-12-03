FROM php:8.1-cli

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions @composer xdebug
COPY ./xdebug.ini $PHP_INI_DIR/conf.d/xdebug.ini

WORKDIR /project
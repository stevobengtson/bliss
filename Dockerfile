#syntax=docker/dockerfile:1.4

# Versions
FROM mlocati/php-extension-installer:2 AS php_extension_installer_upstream
FROM composer/composer:2-bin AS composer_upstream
FROM ghcr.io/roadrunner-server/roadrunner:2023.3.8 AS roadrunner

FROM php:8.3-cli-alpine AS base

WORKDIR /site

# persistent / runtime deps
# hadolint ignore=DL3018
RUN apk update && apk upgrade && apk add --no-cache \
		acl \
		file \
		gettext \
		git \
        libzip-dev \
        supervisor \
        bash \
	;

# php extensions installer: https://github.com/mlocati/docker-php-extension-installer
COPY --from=php_extension_installer_upstream --link /usr/bin/install-php-extensions /usr/local/bin/

RUN set -eux; \
	install-php-extensions \
        redis \
		apcu \
		intl \
        pdo_pgsql \
		opcache \
		zip \
        intl \
        sockets \
	;

RUN docker-php-ext-configure pcntl --enable-pcntl \
    && docker-php-ext-install pcntl;

###> recipes ###
###< recipes ###

# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV PATH="${PATH}:/root/.composer/vendor/bin"
COPY --from=composer_upstream --link /composer /usr/bin/composer

COPY --from=roadrunner /usr/bin/rr /usr/local/bin/rr

HEALTHCHECK --start-period=60s CMD curl -f http://localhost:2019/metrics || exit 1

COPY ./docker/app/ /app
COPY ./docker/etc/php/conf.d/* /usr/local/etc/php/conf.d/
COPY ./docker/etc/supervisord/* /etc/supervisord/
COPY ./docker/etc/supervisord.conf /etc/

RUN chmod +x /app/entry.sh /app/entry.d/*.sh

ENTRYPOINT ["/app/entry.sh"]
CMD [ "-D" ]

FROM base AS prod

ENV APP_ENV=prod
ENV APP_DEBUG=0
ENV RR_CONFIG=.rr.yaml

COPY docker/dev/app/entry.d/* /app/entry.d/
RUN rm /usr/local/etc/php/conf.d/xdebug.ini

COPY composer.json .
COPY composer.lock .

RUN composer install --no-dev --optimize-autoloader
RUN php bin/console cache:clear

# No more need for composer
RUN rm /usr/bin/composer

# Compile assets
RUN php bin/console asset-map:compile


FROM base AS dev

ENV APP_ENV=dev
ENV RR_CONFIG=.rr.dev.yaml

COPY docker/dev/app/entry.d/* /app/entry.d/
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

RUN set -eux; \
	install-php-extensions \
		xdebug \
	;

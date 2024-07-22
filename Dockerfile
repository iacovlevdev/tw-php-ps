FROM --platform=${TARGETPLATFORM:-linux/amd64} ghcr.io/roadrunner-server/roadrunner:latest as roadrunner
FROM --platform=${TARGETPLATFORM:-linux/amd64} php:8.1-alpine

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
COPY --from=roadrunner /usr/bin/rr /usr/local/bin/rr
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

COPY ./src /app

WORKDIR /app

RUN apk update
RUN install-php-extensions sockets curl

CMD ["rr", "serve"]
#!/bin/bash

envsubst '$NGINX_ROOT $NGINX_FPM_HOST $NGINX_FPM_PORT' < /etc/nginx/fpm.tmpl > /etc/nginx/conf.d/default.conf
exec nginx -g "daemon off;"

WORKDIR /usr/src/app

# Copy the example environment file
RUN cp .env.example .env

# Generate the application key
RUN php artisan key:generate


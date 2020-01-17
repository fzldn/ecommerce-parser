FROM php:7.3-fpm-alpine

# Install Packages
RUN apk --no-cache add bash git supervisor nginx

# Install php extensions
RUN docker-php-ext-install pdo_mysql

# Configur Supervisor
ADD config/supervisord.conf /etc/supervisord.conf

# Configure Nginx
RUN mkdir -p /run/nginx
ADD config/site.conf /etc/nginx/conf.d/default.conf

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Expose the port nginx is reachable on
EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]

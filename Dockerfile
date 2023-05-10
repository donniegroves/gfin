FROM php:8.1-apache
ARG ENVIRONMENT
WORKDIR /var/www
COPY . .

# Install any needed PHP extensions
RUN docker-php-ext-install mysqli pdo_mysql sockets && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    apt-get update && apt-get install -y unzip chromium && \
    composer install --no-interaction --no-progress --prefer-dist && \
    sed -i 's|DocumentRoot.*|DocumentRoot /var/www/public|' /etc/apache2/sites-available/000-default.conf && \
    sed -i 's|<Directory.*|<Directory /var/www/public>|' /etc/apache2/apache2.conf && \
    a2enmod rewrite && \
    mkdir -p /var/www/storage/app/public/notifs && \
    chown -R www-data:www-data /var/www

# Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_16.x | bash - && \
    apt-get install -y nodejs && \
    npm i && npm run build

# Update Apache configuration to allow .htaccess overrides
RUN { \
        echo '<Directory /var/www/public>'; \
        echo '    Options Indexes FollowSymLinks'; \
        echo '    AllowOverride All'; \
        echo '    Require all granted'; \
        echo '</Directory>'; \
    } >> /etc/apache2/sites-available/000-default.conf

RUN if [ "$ENVIRONMENT" = "development" ]; then \
        pecl install xdebug && \
        docker-php-ext-enable xdebug && \
        echo 'xdebug.mode=debug,develop' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
        echo 'xdebug.start_with_request=yes' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
        echo 'xdebug.client_host=host.docker.internal' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
        echo 'xdebug.log=/tmp/xdebug.log' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
        touch /tmp/xdebug.log && chown www-data:www-data /tmp/xdebug.log; \
    fi

EXPOSE 80

COPY start.sh /start.sh
RUN chmod +x /start.sh
CMD ["/start.sh"]

# ICT Services Portal API — Laravel 12 / PHP 8.2 on Apache.
# Docroot is public/ (Laravel standard).
# Listens on 8080 to match the ALB target group and the shared app SG.

FROM php:8.2-apache

# Install dependencies including cron
RUN apt-get update && apt-get install -y --no-install-recommends \
      libonig-dev libzip-dev libpng-dev libicu-dev unzip git cron \
    && docker-php-ext-install pdo_mysql mbstring bcmath zip gd intl opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Apache configuration
RUN a2enmod rewrite headers \
    && sed -ri 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf \
    && sed -ri 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf \
    && sed -ri 's/Listen 80$/Listen 8080/' /etc/apache2/ports.conf \
    && sed -ri 's/:80>/:8080>/' /etc/apache2/sites-available/000-default.conf

# SSL / Forwarded Header settings
RUN { \
      echo 'SetEnv HTTPS on'; \
      echo 'SetEnvIf User-Agent "^ELB-HealthChecker" dontlog'; \
    } > /etc/apache2/conf-available/zz-proxy.conf \
    && a2enconf zz-proxy

# PHP configuration
RUN { \
      echo 'memory_limit = 256M'; \
      echo 'upload_max_filesize = 32M'; \
      echo 'post_max_size = 32M'; \
      echo 'date.timezone = Asia/Manila'; \
    } > /usr/local/etc/php/conf.d/zz-app.ini

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Dependency layer
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --no-interaction

COPY . .

# Optimize autoloader and set permissions
RUN composer dump-autoload --optimize --no-dev \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Setup Cron for Laravel Scheduler
RUN echo "* * * * * cd /var/www/html && /usr/local/bin/php artisan schedule:run >> /dev/null 2>&1" > /etc/cron.d/laravel-cron \
    && chmod 0644 /etc/cron.d/laravel-cron \
    && crontab /etc/cron.d/laravel-cron

COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 8080

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]
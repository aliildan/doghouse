FROM php:8.2-fpm
WORKDIR "/app"

# Install git, unzip (required for composer), libpq (required for pgsql extension) and libzip (required for zip extension)
RUN apt-get update && apt-get install -y git unzip libpq-dev libzip-dev

# Install pgsql and redis extensions using Docker's docker-php-ext-install script
RUN docker-php-ext-install pdo_pgsql && docker-php-ext-enable pdo_pgsql
RUN pecl install redis && docker-php-ext-enable redis

# Override the entrypoint
ENTRYPOINT ["php", "artisan", "queue:work", "--daemon"]
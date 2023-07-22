FROM php:8.2-fpm
WORKDIR "/app"
# Install git, unzip (required for composer), libpq (required for pgsql extension) and libzip (required for zip extension)
RUN apt-get update && apt-get install -y git unzip libpq-dev libzip-dev && apt-get install -y nodejs npm

# Install pgsql and redis extensions using Docker's docker-php-ext-install script
RUN docker-php-ext-install pdo_pgsql && docker-php-ext-enable pdo_pgsql
RUN pecl install redis && docker-php-ext-enable redis


# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy entrypoint script
COPY start.sh /start.sh

# Make sure the entrypoint script is executable
RUN chmod +x /start.sh

ENTRYPOINT ["/start.sh"]

# Use PHP 8.2 CLI (or 8.4 as needed)
FROM php:8.2-cli

WORKDIR /app

# Install PHP extensions required by Symfony
RUN apt-get update \
    && apt-get install -y libicu-dev libzip-dev zip \
    && docker-php-ext-install intl pdo_mysql zip \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer

# Copy app code
COPY . /app

# Expose port for PHP built-in server
EXPOSE 8000

# Default command: run built-in server on all interfaces
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]

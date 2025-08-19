FROM php:8.3-cli

# Set working directory
WORKDIR /srv/app

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    libssl-dev \
    bash \
    && docker-php-ext-install mbstring exif pcntl bcmath gd zip \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create directories and set permissions
RUN mkdir -p /srv/app/var/log /srv/app/var/cache /srv/app/storage \
    && chown -R www-data:www-data /srv/app \
    && chmod -R 755 /srv/app

# Expose port 80 for development server
EXPOSE 80

# Default command - can be overridden in docker-compose
CMD ["php", "-S", "0.0.0.0:80", "-t", "public"]

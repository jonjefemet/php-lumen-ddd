FROM php:8.3-cli AS base

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
    && docker-php-ext-install mbstring exif pcntl bcmath gd zip \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

FROM base AS dependencies

# Set working directory for dependencies
WORKDIR /srv/app

# Copy composer files first for better Docker layer caching
COPY composer.json composer.lock ./

# Install dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-cache --ignore-platform-req=ext-mongodb

FROM base AS production

# Set working directory
WORKDIR /srv/app

# Copy dependencies from previous stage
COPY --from=dependencies /srv/app/vendor ./vendor

# Copy application code
COPY . .

# Copy vendor again to ensure it's on top
COPY --from=dependencies /srv/app/vendor ./vendor

# Create required directories and set permissions
RUN mkdir -p /srv/app/var/log /srv/app/var/cache /srv/app/storage \
    && chown -R www-data:www-data /srv/app \
    && chmod -R 755 /srv/app/var /srv/app/storage

# Remove unnecessary files for production
RUN rm -rf /srv/app/tests \
    && rm -rf /srv/app/.git \
    && rm -f /srv/app/composer.json /srv/app/composer.lock

# Switch to non-root user
USER www-data

# Expose port 80
EXPOSE 80

# Default command
CMD ["php", "-S", "0.0.0.0:80", "-t", "public"]

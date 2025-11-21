FROM dunglas/frankenphp:latest-php8.4

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Node.js and npm
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*

# Set working directory
WORKDIR /app

# Copy composer files
COPY composer.json composer.lock symfony.lock ./

# Install PHP dependencies
RUN composer install --no-scripts --no-autoloader --prefer-dist

# Copy package files
COPY package.json package-lock.json ./

# Install Node dependencies
RUN npm ci

# Copy application files
COPY . .

# Generate autoload files
RUN composer dump-autoload --optimize

# Build assets
RUN npm run build

# Copy Caddyfile
COPY Caddyfile /etc/caddy/Caddyfile

# Create var directory and set permissions
RUN mkdir -p var/cache var/log var/data \
    && chown -R www-data:www-data var \
    && chmod -R 775 var

# Expose ports
EXPOSE 80 443

# Set environment to production by default
ENV APP_ENV=prod

# Start FrankenPHP
CMD ["frankenphp", "run", "--config", "/etc/caddy/Caddyfile"]

FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev \
    nginx

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_pgsql pgsql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy application code
COPY . .

# Create .env file from .env.example if it doesn't exist
RUN if [ ! -f .env ]; then cp .env.example .env; fi

# Set permissions
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html/storage

# Install dependencies
RUN composer install --optimize-autoloader --no-dev

# Generate application key if not already set
RUN php artisan key:generate --force

# Make sure the Swagger documentation directory exists
RUN mkdir -p /var/www/html/app/Http/Controllers/Api/Documentation

# Configure nginx
COPY ./infrastructure/docker/nginx/default.conf /etc/nginx/conf.d/default.conf
RUN echo "daemon off;" >> /etc/nginx/nginx.conf

# Create a startup script
RUN echo '#!/bin/bash\n\
php-fpm -D\n\
nginx\n\
' > /usr/local/bin/startup.sh

RUN chmod +x /usr/local/bin/startup.sh

# Expose port 80 for Azure
EXPOSE 80

# Start both PHP-FPM and Nginx
CMD ["/usr/local/bin/startup.sh"]

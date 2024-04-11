FROM php:8.2.0-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    curl \
    gnupg \
    software-properties-common \
    default-mysql-client \
    && apt-get install -y cron\  
    && apt-get install -y supervisor

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Node.js (LTS Version)
RUN curl -sL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Verify that Node.js and npm are installed
RUN node --version \
    && npm --version

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy existing application directory contents
COPY . /var/www

# Copy existing application directory permissions
COPY --chown=www-data:www-data . /var/www

# Copy wait-for-db script
COPY wait-for-db.sh /wait-for-db.sh
RUN chmod +x /wait-for-db.sh
COPY start.sh /start.sh
RUN chmod +x /start.sh

# Copy Supervisor configuration file
COPY docker/supervisor.conf /etc/supervisor/conf.d/supervisor.conf

# Create the cron job
RUN echo "* * * * * cd /var/www && /usr/local/bin/php artisan schedule:run >> /var/log/cron.log 2>&1" > /etc/cron.d/laravel_scheduler

# Give execution rights on the cron job
RUN chmod 0644 /etc/cron.d/laravel_scheduler

# Apply cron job
RUN /usr/bin/crontab /etc/cron.d/laravel_scheduler

# Create the log file to be able to run tail
RUN touch /var/log/cron.log

# Expose port 8000 and 5173 and start php-fpm server
EXPOSE 8000
EXPOSE 5173

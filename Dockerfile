FROM php:8.3-fpm

# Set working directory
WORKDIR /var/www

# Install dependencies yang dibutuhkan Laravel
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm \
    libzip-dev

# Clear cache list apt
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions PHP (termasuk PDO MySQL untuk koneksi databasenya)
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Ambil versi terbaru Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Salin seluruh file aplikasi lokal ke container
COPY . /var/www

# Instal dependensi PHP
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Kompilasi frontend assets menggunakan Vite/NPM
RUN npm install && npm run build

# Berikan hak akses pada storage & cache agar Laravel dapat menuliskannya
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/storage \
    && chmod -R 775 /var/www/bootstrap/cache

# Port bawaan FPM
EXPOSE 9000
CMD ["php-fpm"]

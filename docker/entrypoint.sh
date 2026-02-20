#!/bin/sh
set -e

# Fix permissions pada storage & cache (diperlukan karena volume mount bisa override chown di Dockerfile)
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Jalankan PHP-FPM sebagai proses utama
exec php-fpm

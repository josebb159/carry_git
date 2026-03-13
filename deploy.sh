#!/bin/bash
echo "Iniciando proceso de despliegue..."

# Traer últimos cambios
git pull origin main

# Instalar dependencias (opcional, por si añadieron paquetes)
composer install --no-interaction --prefer-dist --optimize-autoloader

# Ejecutar las migraciones sin pedir confirmación (necesario en producción)
php artisan migrate --force

# Limpiar y cachear configuraciones, rutas y vistas
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "¡Despliegue completado con éxito!"

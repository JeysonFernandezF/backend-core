#!/bin/sh
set -e


# Esperar a que MySQL esté listo (si está disponible)
if [ -n "$DB_HOST" ]; then
    until nc -z "$DB_HOST" "${DB_PORT:-3306}" 2>/dev/null; do
        sleep 2
    done
fi

# Configurar git para evitar problemas de ownership
git config --global --add safe.directory /var/www/html 2>/dev/null || true

# Instalar dependencias de Composer si no existen
if [ ! -f /var/www/html/vendor/autoload.php ]; then
    cd /var/www/html
    composer install --no-interaction --prefer-dist --optimize-autoloader || {
        composer update --no-interaction --prefer-dist --optimize-autoloader
    }
else
    echo "Dependencias de Composer ya existen"
fi

# Verificar y crear .env si no existe
if [ ! -f /var/www/html/.env ]; then
    if [ -f /var/www/html/.env.example ]; then
        echo "Creando .env desde .env.example..."
        cp /var/www/html/.env.example /var/www/html/.env
    fi
fi

# Ejecutar el comando original
echo "▶️  Ejecutando: $@"
exec "$@"


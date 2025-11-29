#!/bin/bash
# Script para iniciar el entorno de desarrollo

echo "🚀 Iniciando entorno de desarrollo CoreSafe..."

# Verificar si existe .env
if [ ! -f .env ]; then
    echo "📝 Creando archivo .env desde .env.example..."
    cp .env.example .env
fi

# Construir y levantar contenedores
echo "🔨 Construyendo contenedores..."
docker-compose -f docker-compose.dev.yml build

echo "⬆️  Levantando servicios..."
docker-compose -f docker-compose.dev.yml up -d

# Esperar a que MySQL esté listo
echo "⏳ Esperando a que MySQL esté listo..."
sleep 10

# Instalar dependencias de Composer
echo "📦 Instalando dependencias de Composer..."
docker-compose -f docker-compose.dev.yml exec -T app composer install

# Instalar dependencias de npm
echo "📦 Instalando dependencias de npm..."
docker-compose -f docker-compose.dev.yml exec -T app npm install

# Generar clave de aplicación
echo "🔑 Generando clave de aplicación..."
docker-compose -f docker-compose.dev.yml exec -T app php artisan key:generate

# Ejecutar migraciones
echo "🗄️  Ejecutando migraciones..."
docker-compose -f docker-compose.dev.yml exec -T app php artisan migrate --force

# Ejecutar seeders (opcional)
read -p "¿Deseas ejecutar los seeders? (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    echo "🌱 Ejecutando seeders..."
    docker-compose -f docker-compose.dev.yml exec -T app php artisan db:seed
fi

# Iniciar queue worker
echo "📬 Iniciando queue worker..."
docker-compose -f docker-compose.dev.yml --profile dev up -d queue

echo "✅ Entorno de desarrollo listo!"
echo "🌐 Aplicación disponible en: http://localhost:8000"
echo ""
echo "Para ver los logs: docker-compose -f docker-compose.dev.yml logs -f"
echo "Para detener: docker-compose -f docker-compose.dev.yml down"



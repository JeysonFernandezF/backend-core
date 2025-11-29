#!/bin/bash
# Script para iniciar el entorno de producción

set -e

echo "🚀 Iniciando entorno de producción CoreSafe..."

# Verificar si existe .env.production
if [ ! -f .env.production ]; then
    echo "⚠️  Archivo .env.production no encontrado"
    echo "📝 Creando .env.production desde .env.example..."
    
    if [ -f .env.example ]; then
        cp .env.example .env.production
        echo "✅ Archivo .env.production creado"
        echo "⚠️  IMPORTANTE: Edita .env.production con tus valores de producción antes de continuar"
        echo ""
        read -p "¿Deseas continuar de todas formas? (y/n) " -n 1 -r
        echo
        if [[ ! $REPLY =~ ^[Yy]$ ]]; then
            echo "❌ Abortando..."
            exit 1
        fi
    else
        echo "❌ No se encontró .env.example"
        exit 1
    fi
fi

# Verificar que los directorios necesarios existen
echo "📁 Verificando directorios..."
mkdir -p storage/logs
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p bootstrap/cache

# Verificar puertos
echo "🔍 Verificando puertos..."
if netstat -tuln 2>/dev/null | grep -q ":8080 " || ss -tuln 2>/dev/null | grep -q ":8080 "; then
    echo "⚠️  El puerto 8080 está en uso"
    echo "   Esto puede causar problemas con Nginx"
    read -p "¿Deseas continuar? (y/n) " -n 1 -r
    echo
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        exit 1
    fi
fi

# Construir imágenes
echo "🔨 Construyendo imágenes de Docker..."
docker compose -f docker-compose.prod.yml build --no-cache

# Detener contenedores existentes (si hay)
echo "🛑 Deteniendo contenedores existentes..."
docker compose -f docker-compose.prod.yml down

# Levantar servicios
echo "⬆️  Levantando servicios de producción..."
docker compose -f docker-compose.prod.yml up -d

# Esperar a que los servicios estén listos
echo "⏳ Esperando a que los servicios estén listos..."
sleep 10

# Verificar estado
echo "📊 Estado de los contenedores:"
docker compose -f docker-compose.prod.yml ps

echo ""
echo "✅ Entorno de producción iniciado!"
echo ""
echo "📋 Servicios disponibles:"
echo "   - Nginx: http://localhost:8080 (puerto interno, Apache hace proxy desde 443)"
echo "   - MySQL: puerto interno (3306 en contenedor)"
echo "   - Redis: puerto interno (6379 en contenedor)"
echo ""
echo "📝 Comandos útiles:"
echo "   Ver logs: docker compose -f docker-compose.prod.yml logs -f"
echo "   Ver estado: docker compose -f docker-compose.prod.yml ps"
echo "   Detener: docker compose -f docker-compose.prod.yml down"
echo "   Reiniciar: docker compose -f docker-compose.prod.yml restart"
echo ""

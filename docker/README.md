# Docker Setup para CoreSafe

Este directorio contiene la configuración de Docker para desarrollo y producción del proyecto CoreSafe.

## Estructura

```
docker/
├── nginx/
│   ├── dev/          # Configuración de Nginx para desarrollo
│   └── prod/         # Configuración de Nginx para producción
├── php/
│   ├── dev/          # Configuración de PHP para desarrollo
│   └── prod/         # Configuración de PHP para producción
├── mysql/
│   ├── my.cnf        # Configuración de MySQL para desarrollo
│   └── prod/         # Configuración de MySQL para producción
└── redis/
    └── redis.conf    # Configuración de Redis para producción
```

## Desarrollo

### Inicio rápido

1. **Usando el script (recomendado):**
   ```bash
   chmod +x docker/start-dev.sh
   ./docker/start-dev.sh
   ```

2. **Manualmente:**
   ```bash
   # Construir y levantar contenedores
   docker-compose -f docker-compose.dev.yml up -d --build
   
   # Instalar dependencias
   docker-compose -f docker-compose.dev.yml exec app composer install
   docker-compose -f docker-compose.dev.yml exec app npm install
   
   # Configurar aplicación
   docker-compose -f docker-compose.dev.yml exec app php artisan key:generate
   docker-compose -f docker-compose.dev.yml exec app php artisan migrate
   
   # Iniciar queue worker
   docker-compose -f docker-compose.dev.yml --profile dev up -d queue
   ```

### Servicios disponibles

- **App**: http://localhost:8000 (artisan serve)
- **MySQL**: localhost:3306
- **Redis**: localhost:6379
- **Queue Worker**: Se ejecuta automáticamente con el perfil `dev`

### Comandos útiles

```bash
# Ver logs
docker-compose -f docker-compose.dev.yml logs -f

# Ejecutar comandos artisan
docker-compose -f docker-compose.dev.yml exec app php artisan [comando]

# Acceder a la consola del contenedor
docker-compose -f docker-compose.dev.yml exec app sh

# Detener servicios
docker-compose -f docker-compose.dev.yml down

# Detener y eliminar volúmenes
docker-compose -f docker-compose.dev.yml down -v
```

## Producción

### Requisitos previos

1. Crear archivo `.env.production` con las siguientes variables:
   ```env
   APP_NAME=CoreSafe
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://tu-dominio.com
   
   DB_DATABASE=coresafe
   DB_USERNAME=coresafe_user
   DB_PASSWORD=tu_password_seguro
   DB_ROOT_PASSWORD=root_password_seguro
   
   REDIS_PASSWORD=redis_password_seguro
   
   QUEUE_CONNECTION=redis
   ```

### Inicio rápido

1. **Usando el script:**
   ```bash
   chmod +x docker/start-prod.sh
   ./docker/start-prod.sh
   ```

2. **Manualmente:**
   ```bash
   # Construir y levantar contenedores
   docker-compose -f docker-compose.prod.yml up -d --build
   ```

### Servicios en producción

- **App**: Aplicación Laravel con PHP-FPM
- **Nginx**: Servidor web (puerto 80/443)
- **Queue Worker**: Procesa trabajos de cola
- **Scheduler**: Ejecuta tareas programadas (cron)
- **MySQL**: Base de datos
- **Redis**: Cache y colas

### Comandos útiles

```bash
# Ver logs
docker-compose -f docker-compose.prod.yml logs -f

# Ver logs de un servicio específico
docker-compose -f docker-compose.prod.yml logs -f queue

# Reiniciar un servicio
docker-compose -f docker-compose.prod.yml restart queue

# Detener servicios
docker-compose -f docker-compose.prod.yml down

# Backup de base de datos
docker-compose -f docker-compose.prod.yml exec mysql mysqldump -u root -p coresafe > backup.sql
```

## Queue Workers

### Desarrollo

El queue worker se ejecuta automáticamente cuando se usa el perfil `dev`:

```bash
docker-compose -f docker-compose.dev.yml --profile dev up -d queue
```

### Producción

El queue worker está configurado para reiniciarse automáticamente y procesar trabajos con Redis:

```bash
# Ver logs del queue worker
docker-compose -f docker-compose.prod.yml logs -f queue

# Reiniciar queue worker
docker-compose -f docker-compose.prod.yml restart queue
```

## Troubleshooting

### Problemas comunes

1. **Puerto 3306 ya en uso:**
   - Cambia el puerto en `docker-compose.dev.yml`: `"3307:3306"`

2. **Permisos de storage:**
   ```bash
   docker-compose -f docker-compose.dev.yml exec app chmod -R 775 storage bootstrap/cache
   ```

3. **Queue worker no procesa trabajos:**
   - Verifica que las migraciones de jobs estén ejecutadas
   - Revisa la configuración de `QUEUE_CONNECTION` en `.env`

4. **Errores de conexión a MySQL:**
   - Espera unos segundos para que MySQL termine de iniciar
   - Verifica las credenciales en `.env`

## Notas

- Los volúmenes de desarrollo montan el código fuente directamente para desarrollo en tiempo real
- En producción, solo se montan los directorios necesarios (storage, cache)
- Los backups de MySQL se guardan en `docker/mysql/backups/`



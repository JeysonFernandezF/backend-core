# 🚀 Guía Rápida de Docker para CoreSafe

## Inicio Rápido - Desarrollo

### Opción 1: Usando Make (recomendado si tienes Make instalado)

```bash
cd docker
make dev
```

### Opción 2: Usando el script bash

```bash
chmod +x docker/start-dev.sh
./docker/start-dev.sh
```

### Opción 3: Manualmente

```bash
# 1. Construir y levantar contenedores
docker-compose -f docker-compose.dev.yml up -d --build

# 2. Instalar dependencias
docker-compose -f docker-compose.dev.yml exec app composer install
docker-compose -f docker-compose.dev.yml exec app npm install

# 3. Configurar aplicación
docker-compose -f docker-compose.dev.yml exec app php artisan key:generate
docker-compose -f docker-compose.dev.yml exec app php artisan migrate

# 4. El queue worker se inicia automáticamente
```

**Aplicación disponible en:** http://localhost:8000

## Inicio Rápido - Producción

### 1. Crear archivo de configuración

```bash
cp .env.production.example .env.production
# Editar .env.production con tus valores
```

### 2. Iniciar servicios

```bash
# Usando Make
cd docker
make prod

# O manualmente
docker-compose -f docker-compose.prod.yml up -d --build
```

**Aplicación disponible en:** http://localhost

## Comandos Útiles

### Desarrollo

```bash
# Ver logs
docker-compose -f docker-compose.dev.yml logs -f

# Ver logs del queue worker
docker-compose -f docker-compose.dev.yml logs -f queue

# Ejecutar comandos artisan
docker-compose -f docker-compose.dev.yml exec app php artisan migrate

# Acceder a la consola
docker-compose -f docker-compose.dev.yml exec app sh

# Detener servicios
docker-compose -f docker-compose.dev.yml down
```

### Producción

```bash
# Ver logs
docker-compose -f docker-compose.prod.yml logs -f

# Ver logs del queue worker
docker-compose -f docker-compose.prod.yml logs -f queue

# Reiniciar queue worker
docker-compose -f docker-compose.prod.yml restart queue

# Reiniciar aplicación
docker-compose -f docker-compose.prod.yml restart app

# Detener servicios
docker-compose -f docker-compose.prod.yml down
```

## Queue Workers

### Desarrollo

El queue worker se inicia automáticamente con el entorno de desarrollo usando `database` como driver.

### Producción

El queue worker está configurado para usar `redis` como driver y se ejecuta automáticamente. 

Para verificar que está funcionando:

```bash
# Ver logs del queue worker
docker-compose -f docker-compose.prod.yml logs -f queue

# Verificar que el contenedor está corriendo
docker ps | grep queue
```

## Troubleshooting

### Puerto 3306 ya en uso

Edita `docker-compose.dev.yml` y cambia el puerto:
```yaml
ports:
  - "3307:3306"  # Cambia 3306 por 3307
```

### Permisos de storage

```bash
docker-compose -f docker-compose.dev.yml exec app chmod -R 775 storage bootstrap/cache
```

### Queue worker no procesa trabajos

1. Verifica que las migraciones estén ejecutadas:
   ```bash
   docker-compose -f docker-compose.dev.yml exec app php artisan migrate
   ```

2. Verifica la configuración de QUEUE_CONNECTION en `.env`

3. Verifica los logs:
   ```bash
   docker-compose -f docker-compose.dev.yml logs queue
   ```

### MySQL no responde

Espera unos segundos después de iniciar los contenedores. MySQL tarda un poco en estar listo.

```bash
# Verificar estado
docker-compose -f docker-compose.dev.yml ps

# Ver logs de MySQL
docker-compose -f docker-compose.dev.yml logs mysql
```

## Servicios Disponibles

### Desarrollo
- **App**: http://localhost:8000
- **MySQL**: localhost:3306
- **Redis**: localhost:6379
- **Vite** (opcional): localhost:5173

### Producción
- **App**: http://localhost (puerto 80)
- **MySQL**: Solo accesible desde la red Docker
- **Redis**: Solo accesible desde la red Docker

## Notas Importantes

1. **Volúmenes**: En desarrollo, el código se monta directamente. En producción, solo se montan `storage` y `bootstrap/cache`.

2. **Queue Workers**: 
   - Desarrollo: Usa `database` driver
   - Producción: Usa `redis` driver (mejor rendimiento)

3. **Seguridad**: 
   - En producción, asegúrate de usar contraseñas seguras
   - Configura SSL/HTTPS en Nginx
   - No expongas MySQL y Redis al exterior en producción

4. **Backups**: Los backups de MySQL se guardan en `docker/mysql/backups/`



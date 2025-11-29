# Plantilla de Variables de Entorno para Producción

Crea un archivo `.env.production` con las siguientes variables:

```env
# Configuración de producción para CoreSafe

APP_NAME=CoreSafe
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://tu-dominio.com

LOG_CHANNEL=stack
LOG_LEVEL=error

# Base de datos MySQL
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=coresafe
DB_USERNAME=coresafe_user
DB_PASSWORD=tu_password_seguro_aqui
DB_ROOT_PASSWORD=root_password_seguro_aqui

# Redis
REDIS_HOST=redis
REDIS_PASSWORD=redis_password_seguro_aqui
REDIS_PORT=6379
REDIS_CLIENT=phpredis

# Queue Configuration
QUEUE_CONNECTION=redis

# Mail Configuration (ajusta según tu proveedor)
MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@tu-dominio.com"
MAIL_FROM_NAME="${APP_NAME}"

# Session
SESSION_DRIVER=redis
SESSION_LIFETIME=120

# Cache
CACHE_DRIVER=redis
CACHE_PREFIX=coresafe

# JWT Configuration
JWT_SECRET=
JWT_TTL=60
```

## Instrucciones

1. Copia este contenido a un archivo llamado `.env.production` en la raíz del proyecto
2. Reemplaza todos los valores de ejemplo con tus valores reales
3. **IMPORTANTE**: Usa contraseñas seguras y únicas
4. Genera `APP_KEY` ejecutando: `php artisan key:generate`
5. Genera `JWT_SECRET` ejecutando: `php artisan jwt:secret`

## Notas de Seguridad

- **NUNCA** commitees el archivo `.env.production` al repositorio
- Usa contraseñas fuertes y diferentes para cada servicio
- En producción, considera usar un gestor de secretos (AWS Secrets Manager, HashiCorp Vault, etc.)



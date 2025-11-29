.PHONY: dev dev-build dev-up dev-down dev-logs dev-queue
.PHONY: prod prod-build prod-up prod-down prod-logs
.PHONY: help

help: ## Mostrar esta ayuda
	@echo "Comandos disponibles:"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "  \033[36m%-20s\033[0m %s\n", $$1, $$2}'

# Desarrollo
dev: dev-up ## Iniciar entorno de desarrollo
	@echo "Entorno de desarrollo iniciado"

dev-build: ## Construir contenedores de desarrollo
	docker-compose -f docker-compose.dev.yml build

dev-up: ## Levantar servicios de desarrollo
	docker-compose -f docker-compose.dev.yml up -d
	@echo "Esperando a que MySQL esté listo..."
	@sleep 10
	docker-compose -f docker-compose.dev.yml exec -T app composer install || true
	docker-compose -f docker-compose.dev.yml exec -T app npm install || true
	docker-compose -f docker-compose.dev.yml --profile dev up -d queue

dev-down: ## Detener servicios de desarrollo
	docker-compose -f docker-compose.dev.yml down

dev-logs: ## Ver logs de desarrollo
	docker-compose -f docker-compose.dev.yml logs -f

dev-queue: ## Iniciar queue worker de desarrollo
	docker-compose -f docker-compose.dev.yml --profile dev up -d queue

dev-shell: ## Acceder a la consola del contenedor app
	docker-compose -f docker-compose.dev.yml exec app sh

dev-artisan: ## Ejecutar comando artisan (uso: make dev-artisan CMD="migrate")
	docker-compose -f docker-compose.dev.yml exec app php artisan $(CMD)

# Producción
prod: prod-up ## Iniciar entorno de producción
	@echo "Entorno de producción iniciado"

prod-build: ## Construir contenedores de producción
	docker-compose -f docker-compose.prod.yml build --no-cache

prod-up: ## Levantar servicios de producción
	@if [ ! -f .env.production ]; then \
		echo "Error: No se encontró .env.production"; \
		exit 1; \
	fi
	docker-compose -f docker-compose.prod.yml up -d
	@echo "Esperando a que MySQL esté listo..."
	@sleep 15

prod-down: ## Detener servicios de producción
	docker-compose -f docker-compose.prod.yml down

prod-logs: ## Ver logs de producción
	docker-compose -f docker-compose.prod.yml logs -f

prod-queue-logs: ## Ver logs del queue worker
	docker-compose -f docker-compose.prod.yml logs -f queue

prod-restart-queue: ## Reiniciar queue worker
	docker-compose -f docker-compose.prod.yml restart queue

# Utilidades
clean: ## Limpiar contenedores y volúmenes
	docker-compose -f docker-compose.dev.yml down -v
	docker-compose -f docker-compose.prod.yml down -v

backup-db: ## Hacer backup de la base de datos (dev)
	docker-compose -f docker-compose.dev.yml exec mysql mysqldump -u root -proot_password coresafe > backup_$(shell date +%Y%m%d_%H%M%S).sql



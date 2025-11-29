# README — Levantamiento de servicio Laravel con Herd

Cómo levantar el proyecto en local usando **Laravel Herd** y complementa los pasos con buenas prácticas y alternativas (SQLite/MySQL), además de comandos útiles.

---

## Requisitos

- **macOS** (para usar Laravel Herd).
- **Laravel Herd** instalado (incluye PHP y Nginx embebidos).
- **Composer** (Herd también lo instala, pero puedes verificar con `composer -V`).
- **Node.js + npm** (si el proyecto usa Vite/Frontend).
- **MySQL** (opcional si no usas SQLite; puedes usar MariaDB o Docker).

> En Linux/Windows (sin Herd) puedes usar `php artisan serve` o tu stack local preferido (Docker, Laragon, etc.).

---

## 1) Instalar Laravel Herd

1. Descarga e instala Laravel Herd desde su sitio oficial.
2. Abre Herd y asegúrate de que **PHP** y **Nginx** estén activos.
3. (Opcional) Añade la carpeta donde guardas tus proyectos para que Herd los detecte como sitios.  
   Herd suele exponerlos en `https://<nombre>.test`.

---

## 2) Importar el repositorio

```bash
cd <carpeta-proyectos>
git clone https://github.com/CoreSafe/web-coresafe.git
cd <carpeta-del-proyecto>
```
## 3) Instalar dependencias PH

```bash
composer install
```

## 4) Config var entorno


```bash
cp .env.example .env
```
// Si se desea con SQLite se modifica el .env  dejandolo así

```env
DB_CONNECTION=sqlite
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=laravel
# DB_USERNAME=root
# DB_PASSWORD=
```

## 5) Generar la APP_KEY

```bash
php artisan key:generate
```

## 6) Preparar base de datos

Reinicio completo + seeders

```bash
php artisan migrate:fresh --seed
```

Solo aplicar cambios y poblar

```bash
php artisan migrate --seed
```

Solo migrar

```bash
php artisan migrate 
```




<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

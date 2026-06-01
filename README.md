# Portfolio — Pablo Millaquen

Sitio web personal tipo portafolio construido con **Laravel 12** (API REST) + **Vue 3** (SPA) con soporte completo para **inglés/español**.

## Stack

- **Backend:** Laravel 12, PHP 8.3, MySQL
- **Frontend:** Vue 3 (Composition API, `<script setup>`), Vue Router 5, Axios
- **CSS:** Tailwind CSS v4
- **Build:** Vite 7 + `laravel-vite-plugin`

## Funcionalidades

- Vitrina de proyectos con galería de medios, enlaces demo/repo y tags de tecnologías
- Blog con posts internos y externos
- Cursos y certificaciones
- Formulario de contacto
- Panel administrativo con CRUD completo (proyectos, posts, cursos, redes sociales, configuración del sitio)
- Alternancia de idioma (inglés/español)
- Tema claro/oscuro

## Requisitos

- PHP 8.3+
- Composer
- Node.js 20+
- MySQL

## Instalación

```bash
# Clonar el repositorio
git clone <repo-url>
cd portfolio

# Instalar dependencias de PHP
composer install

# Configurar entorno
cp .env.example .env
php artisan key:generate

# Configurar base de datos en .env y luego:
php artisan migrate

# Instalar dependencias frontend
npm install

# Compilar assets
npm run build
```

## Desarrollo

```bash
# Inicia servidor Laravel + queue + logs + Vite HMR
composer dev
```

## Admin

Acceder a `/admin/login` e iniciar sesión con un usuario que tenga `is_admin = true` en la tabla `users`.

## Licencia

MIT

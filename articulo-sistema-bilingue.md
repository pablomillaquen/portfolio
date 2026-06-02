# Sistema Bilingüe en Laravel + Vue: Implementación Actual y Alternativas

## Introducción

Este artículo documenta la forma en que implementamos un sistema bilingüe (español e inglés) en una aplicación Laravel 12 con Vue 3 SPA, y explora alternativas como la librería [`Laravel-Lang/lang`](https://github.com/Laravel-Lang/lang) y otros enfoques.

---

## 1. Implementación Actual

### Arquitectura general

| Componente | Tecnología |
|---|---|
| Backend | Laravel 12 |
| Frontend | Vue 3 SPA |
| Base de datos | MySQL con columnas JSON |
| Persistencia de idioma | `localStorage` en el navegador |

### Flujo de cambio de idioma

```
Usuario hace clic en EN/ES
  → Vue actualiza ref locale
    → Se guarda en localStorage ('portfolio-locale')
      → Se re-fetchean los endpoints con ?locale=es|en
        → Laravel resuelve con TranslatableContent
          → Respuesta JSON ya traducida
            → Vue renderiza directamente
```

### 1.1 Almacenamiento: Columnas JSON en la base de datos

Cada campo traducible se almacena como un objeto JSON con las claves `es` y `en`:

```sql
-- Ejemplo en la tabla `projects`
title       JSON  →  {"es": "Título del proyecto", "en": "Project title"}
summary     JSON  →  {"es": "Resumen en español", "en": "English summary"}
description JSON  →  {"es": "Descripción larga...", "en": "Long description..."}
```

**Ventaja:** Simple, sin tablas adicionales ni joins.

**Desventaja:** Acopla el esquema a los idiomas soportados; agregar un nuevo idioma requiere migración y actualizar toda la data existente.

### 1.2 Helper personalizado: `TranslatableContent`

```php
namespace App\Support;

class TranslatableContent
{
    public static function text(mixed $value, string $locale): mixed
    {
        if (! is_array($value)) {
            return $value;
        }

        return $value[$locale] ?? $value['en'] ?? $value['es'] ?? reset($value);
    }

    public static function deep(mixed $value, string $locale): mixed
    {
        if (! is_array($value)) {
            return $value;
        }

        if (array_key_exists('en', $value) || array_key_exists('es', $value)) {
            return self::text($value, $locale);
        }

        return array_map(fn ($item) => self::deep($item, $locale), $value);
    }
}
```

- `text()`: Resuelve un campo plano (`{"es": "Hola", "en": "Hello"}`).
- `deep()`: Resuelve estructuras anidadas (ej. `details`, `settings`).

### 1.3 Controllers: Lectura del locale

```php
$locale = $request->string('locale', 'en')->toString();

'title' => TranslatableContent::text($project->title, $locale),
'details' => TranslatableContent::deep($project->details ?? [], $locale),
```

### 1.4 Frontend: Traducciones inline

Para textos estáticos de la UI se usan expresiones ternarias directamente en los templates de Vue:

```html
<h1>{{ locale === 'es' ? 'Portafolio' : 'Portfolio' }}</h1>
<RouterLink to="/projects">
  {{ locale === 'es' ? 'Ver proyectos' : 'View projects' }}
</RouterLink>
```

Para textos navegacionales se usa un objeto con ambas versiones:

```js
const links = [
    { to: '/posts', label: { es: 'Posts', en: 'Posts' } },
    { to: '/projects', label: { es: 'Proyectos', en: 'Projects' } },
    { to: '/courses', label: { es: 'Cursos', en: 'Courses' } },
];
```

### 1.5 Admin: Validación bilingüe

Se valida que ambos idiomas sean enviados:

```php
'title' => ['required', 'array'],
'title.es' => ['required', 'string'],
'title.en' => ['required', 'string'],
```

### 1.6 Ventajas del enfoque actual

- **Sin dependencias externas:** No requiere paquetes Composer ni npm.
- **Simple y directo:** Fácil de entender y depurar.
- **Una sola fuente de verdad:** Los datos traducidos viven con el modelo.
- **Rápido:** Sin joins ni tablas extra.

### 1.7 Desventajas

- **Escalabilidad limitada:** Agregar un nuevo idioma implica reestructurar todas las columnas JSON y actualizar cada registro.
- **Mezcla de concerns:** Los datos de traducción están acoplados al esquema de negocio.
- **Sin herramienta de gestión de traducciones:** No hay forma de auditar traducciones faltantes, exportar/importar, o colaborar con traductores.
- **Duplicación en frontend:** Las traducciones de UI están esparcidas en el código Vue como strings hardcodeados.

---

## 2. Alternativas

### 2.1 Laravel-Lang/lang

**Repositorio:** https://github.com/Laravel-Lang/lang

Esta librería provee archivos de traducción para los mensajes del framework Laravel (validación, auth, paginación, etc.) en más de 100 idiomas.

**¿Qué resuelve?**
- Traduce todos los mensajes nativos de Laravel (reglas de validación `required`, `email`, `unique`, etc.).
- Se instala via Composer y se publican los archivos `lang/es/*.json`.

```bash
composer require laravel-lang/lang
php artisan lang:add es
```

Esto genera archivos como `lang/es.json` con las traducciones oficiales de Laravel.

**¿Qué NO resuelve?**
- No es un sistema de gestión de traducciones para el contenido del negocio (posts, proyectos, etc.).
- No tiene relación con el frontend Vue.

**Conclusión:** Es complementario a cualquier sistema de traducción de contenido. Debería usarse **junto con** otra solución para el contenido de negocio, no como reemplazo.

### 2.2 spatie/laravel-translatable

**Repositorio:** https://github.com/spatie/laravel-translatable

Paquete que permite almacenar traducciones en columnas JSON de forma elegante, muy similar a nuestra implementación actual pero con una capa de abstracción.

```php
use Spatie\Translatable\HasTranslations;

class Project extends Model
{
    use HasTranslations;

    public $translatable = ['title', 'summary', 'description'];
}
```

```php
$project->title = ['es' => 'Título', 'en' => 'Title'];
$project->save();

echo $project->getTranslation('title', 'es');
echo $project->title;  // Usa el locale de la app
```

**Ventajas sobre nuestra implementación:**
- Abstracción completa, elimina la necesidad de `TranslatableContent`.
- Soporta locales dinámicos sin modificar el esquema.
- Mutators y accessors automáticos.

### 2.3 dimsav/laravel-translatable

**Repositorio:** https://github.com/dimsav/laravel-translatable

Enfoque distinto: usa **tablas separadas** para las traducciones.

```
projects
  ├── id
  ├── featured
  └── created_at

project_translations
  ├── id
  ├── project_id (FK)
  ├── locale       (en, es)
  ├── title
  ├── summary
  └── description
```

```php
class Project extends Model
{
    use \Dimsav\Translatable\Translatable;

    public $translatedAttributes = ['title', 'summary', 'description'];
}
```

**Ventajas:**
- Separación completa de datos y traducciones.
- Fácil agregar nuevos idiomas (solo insertar nuevas filas).
- Consultas eficientes con joins.

**Desventajas:**
- Más tablas y migraciones.
- Consultas ligeramente más complejas.

### 2.4 Vue I18n + API de traducciones

**Repositorio:** https://github.com/kazupon/vue-i18n

Para el frontend, usar `vue-i18n` centraliza todas las traducciones de la UI en archivos JSON:

```js
// locales/es.json
{
  "nav": {
    "projects": "Proyectos",
    "posts": "Posts",
    "courses": "Cursos"
  },
  "home": {
    "title": "Portafolio"
  }
}
```

```vue
<template>
  <h1>{{ $t('home.title') }}</h1>
  <RouterLink to="/projects">{{ $t('nav.projects') }}</RouterLink>
</template>
```

**Ventajas sobre las ternarias inline:**
- Traducciones centralizadas y mantenibles.
- Separación de concerns (UI text vs. lógica).
- Fácil agregar un tercer idioma.

### 2.5 Tabla `translations` genérica (EAV)

Una tabla universal para todas las traducciones:

```php
Schema::create('translations', function (Blueprint $table) {
    $table->id();
    $table->morphs('translatable');  // translatable_id, translatable_type
    $table->string('field');
    $table->string('locale', 10);
    $table->text('value');
    $table->unique(['translatable_id', 'translatable_type', 'field', 'locale'], 'translations_unique');
});
```

**Ventaja:** Un solo lugar para todas las traducciones, completamente dinámico.

**Desventaja:** Rendimiento (múltiples queries), sin tipado, sin validación a nivel de columna.

---

## 3. Recomendación

Para un proyecto pequeño-mediano con 2 idiomas fijos, nuestra implementación actual con columnas JSON es perfectamente razonable.

Si el proyecto creciera o necesitara:
- **Mantener traducciones del framework:** Agregar `laravel-lang/lang`.
- **Abstraer las columnas JSON:** Migrar a `spatie/laravel-translatable`.
- **Soportar 3+ idiomas:** Usar `dimsav/laravel-translatable` con tablas separadas.
- **Centralizar textos de UI:** Agregar `vue-i18n` en el frontend.

### Stack recomendado para crecimiento

```
Backend:  spatie/laravel-translatable + laravel-lang/lang
Frontend: vue-i18n
API:      locale vía query param (actual) o Accept-Language header
```

---

## 4. Conclusión

No hay una solución única para todos los casos. La implementación actual con columnas JSON y un helper custom es liviana y funciona bien para 2 idiomas. Las alternativas presentadas ofrecen mejores herramientas para escalar, mantener y colaborar en proyectos con más idiomas o equipos más grandes.

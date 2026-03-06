# MuyLocal

Base SPA limpia con autenticación y sistema de roles/permisos. Laravel 12 + Vue 3.

## Stack

- **Backend:** Laravel 12, PHP 8.2+, MySQL
- **Frontend:** Vue 3 SPA, Vue Router 4, Vite 4, Bootstrap 5
- **Auth:** Sanctum (API tokens), Spatie Laravel Permission (RBAC)
- **Otros:** Cloudinary (imágenes)

## Estructura del proyecto

```
app/Http/Controllers/Controller.php        # Base controller
app/Http/Controllers/AuthController.php    # Login, register, logout, me
app/Models/User.php                        # HasApiTokens, HasRoles, belongsTo(Company)
app/Models/Company.php                     # Empresa base (User depende de ella)
config/fortify.php                         # Desactivado (views=false, features=[])
resources/js/app.js                        # Entry point Vue
resources/js/services/api.js               # Cliente axios con auth interceptors
resources/js/stores/auth.js                # Estado reactivo de autenticación
resources/js/components/                   # Header, Sidebar, Footer, PublicApp, AdminApp
resources/views/                           # Login.vue, Register.vue, Home.vue, About.vue, app.blade.php
resources/router/index.js                  # Rutas + navigation guards
routes/api.php                             # Endpoints API con Sanctum
routes/web.php                             # Catch-all para SPA
```

## Base de datos

Tablas activas: `users`, `companies`, `company_types`, `industries`, `provinces`, `cities`, `permissions`, `roles`, `model_has_permissions`, `model_has_roles`, `role_has_permissions`, `personal_access_tokens`, `sessions`, `password_resets`, `failed_jobs`

## Autenticación

- Token-based via API: `/api/login`, `/api/register`, `/api/me`, `/api/logout`
- Token se guarda en `localStorage` como `auth_token`
- Axios interceptor agrega header `Authorization: Bearer` automáticamente
- Vue Router guards protegen rutas con `meta.requiresAuth` / `meta.guest`
- Fortify está instalado pero **desactivado** — no usar sus rutas

## Comandos

```bash
npm run dev          # Vite dev server
npm run build        # Build producción
php artisan serve    # Laravel dev server
php artisan migrate  # Ejecutar migraciones
```

## Convenciones

- Idioma de la interfaz: **Español**
- API responses en JSON, errores de validación en formato Laravel estándar (422)
- Rutas API bajo `/api/` con middleware `auth:sanctum` para protegidas
- Componentes Vue en Options API
- Sin Vuex/Pinia — estado reactivo simple con `reactive()` + `computed()`
- Old-style Kernel.php (no migrado al patrón Laravel 11+ bootstrap/app.php)

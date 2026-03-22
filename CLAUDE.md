# MuyLocal

Plataforma para generar tarjetas de presentación digitales (minisitios) para empresas y sus empleados. Laravel 12 + Vue 3 SPA.

## Stack

- **Backend:** Laravel 12, PHP 8.2+, MySQL
- **Frontend:** Vue 3 SPA (Options API), Vue Router 4, Vite 4, Bootstrap 5, Bootstrap Icons
- **Auth:** Sanctum (API tokens), Spatie Laravel Permission (RBAC)
- **Otros:** Cloudinary (imágenes)

## Estructura del proyecto

```
app/
├── Http/Controllers/
│   ├── AuthController.php           # Login, register, logout, me
│   ├── CompanyController.php        # CRUD empresas
│   ├── CompanySettingController.php # API configuración plantillas
│   ├── CardController.php           # CRUD tarjetas (anidado bajo empresa)
│   ├── ServiceController.php        # CRUD servicios
│   ├── ProductController.php        # CRUD productos
│   └── PublicCardController.php     # Vistas públicas de tarjetas
├── Models/
│   ├── User.php                     # HasApiTokens, HasRoles, belongsTo(Company)
│   ├── Company.php                  # hasMany(Card, Service, Product), hasOne(CompanySetting)
│   ├── CompanySetting.php           # Configuración de plantilla (JSON)
│   ├── Card.php                     # Tarjeta de presentación individual
│   ├── Service.php                  # Servicios de la empresa
│   └── Product.php                  # Productos de la empresa

config/
├── templates.php                    # Schemas de plantillas disponibles

resources/
├── js/
│   ├── app.js                       # Entry point Vue
│   ├── services/
│   │   ├── api.js                   # Cliente axios con auth interceptors
│   │   ├── companyService.js        # API empresas
│   │   ├── cardService.js           # API tarjetas
│   │   ├── settingService.js        # API configuración plantillas
│   │   └── publicCardService.js     # API vistas públicas
│   ├── stores/
│   │   └── auth.js                  # Estado reactivo de autenticación
│   ├── components/
│   │   ├── Header.vue, Sidebar.vue, Footer.vue
│   │   ├── AdminApp.vue, PublicApp.vue
│   │   └── templates/               # Sistema de plantillas
│   │       ├── LivePreview.vue      # Contenedor con CSS Variables
│   │       ├── TemplateModern.vue   # Plantilla moderna
│   │       ├── TemplateClassic.vue  # Plantilla clásica (placeholder)
│   │       └── TemplateMinimal.vue  # Plantilla minimalista (placeholder)
│   └── views/
│       ├── company/                 # CompanyIndex, CompanyCreate, CompanyShow, CompanyEdit
│       ├── card/                    # CardCreate, CardEdit
│       ├── settings/
│       │   └── TemplateEditor.vue   # Editor de plantillas con live preview
│       └── public/                  # CompanyPublic, CardPublic
├── router/
│   └── index.js                     # Rutas Vue + navigation guards
└── views/
    └── app.blade.php                # Template base Laravel

routes/
├── api.php                          # Endpoints API con Sanctum
└── web.php                          # Catch-all para SPA
```

## Base de datos

### Tablas principales
- `users` - Usuarios del sistema
- `companies` - Empresas (name, slug, logo_path, logo_public_id)
- `company_settings` - Configuración de plantilla por empresa (template_name, customization JSON)
- `cards` - Tarjetas de presentación (first_name, last_name, slug, job_title, photo_path, email, mobile_phone, whatsapp, description, is_active)
- `services` - Servicios de empresa (name, description, image_path, order, is_active)
- `products` - Productos de empresa (name, description, price, discount, comment, image_path, order, is_active)

### Tablas de soporte
- `company_types`, `industries`, `provinces`, `cities` - Catálogos
- `permissions`, `roles`, `model_has_*`, `role_has_permissions` - Spatie Permission
- `personal_access_tokens` - Sanctum
- `sessions`, `password_resets`, `failed_jobs` - Laravel

## Sistema de Plantillas Dinámicas

### Arquitectura

El sistema permite múltiples plantillas con personalización visual completa via JSON:

1. **CompanySetting** (modelo): Almacena `template_name` y `customization` (JSON) por empresa
2. **config/templates.php**: Define plantillas disponibles y sus schemas de configuración
3. **TemplateEditor.vue**: Editor visual con controles generados dinámicamente
4. **LivePreview.vue**: Contenedor que inyecta CSS Variables para actualización en tiempo real
5. **TemplateModern.vue**: Plantilla que consume las CSS Variables

### Flujo de datos

```
TemplateEditor.vue
    │
    ├── Carga schema desde API (/api/templates/{name}/schema)
    ├── Carga settings desde API (/api/companies/{id}/settings)
    │
    ├── Genera controles dinámicos desde schema:
    │   ├── type: 'color'  → <input type="color">
    │   ├── type: 'range'  → <input type="range">
    │   ├── type: 'select' → <select>
    │   └── type: 'toggle' → <input type="checkbox">
    │
    ├── customization (objeto reactivo)
    │       │
    │       ▼
    │   computed: cssVariables
    │   Convierte: { general: { colorFondo: '#fff' } }
    │   A: { '--general-color-fondo': '#fff' }
    │
    └── LivePreview.vue :style="cssVariables"
            │
            └── TemplateModern.vue
                Usa: var(--general-color-fondo, #ffffff)
```

### Schema de configuración (config/templates.php)

```php
'modern' => [
    'general' => [
        '_label' => 'Ajustes Generales',
        'colorFondoSuperior' => [
            'type' => 'color',
            'label' => 'Color Fondo Superior',
            'value' => '#ffffff',  // valor por defecto
        ],
        'fontFamily' => [
            'type' => 'select',
            'label' => 'Tipografía',
            'value' => 'Quicksand',
            'options' => ['Quicksand', 'Roboto', 'Open Sans'],
        ],
    ],
    'header' => [ ... ],
    'photo' => [ ... ],
    'profile' => [ ... ],
    'social' => [ ... ],
    'accordion' => [ ... ],
    'footer' => [ ... ],
]
```

### API de Settings

```
GET    /api/templates                         # Lista plantillas disponibles
GET    /api/templates/{name}/schema           # Schema de una plantilla
GET    /api/companies/{id}/settings           # Configuración de empresa
PUT    /api/companies/{id}/settings           # Actualizar configuración
POST   /api/companies/{id}/settings/reset     # Restablecer a defaults
```

### CSS Variables en plantillas

Las plantillas usan CSS Variables que se actualizan reactivamente:

```css
.company-logo {
    width: var(--header-ancho-logo, 150px);
}

.first-name {
    font-size: var(--profile-nombre-tamano, 1.5em);
    color: var(--profile-nombre-color, #333333);
    font-weight: var(--profile-nombre-peso, 600);
}

.social-btn.facebook {
    background: var(--social-fondo-facebook, #3b5998);
}
```

### Sombras dinámicas

Los toggles de sombra usan clases dinámicas de Vue (no se pueden condicionales en CSS):

```html
<img :class="{ 'with-shadow': customization?.photo?.sombra }" ...>
```

```css
.profile-photo.with-shadow {
    box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.2);
}
```

## Rutas Vue

```
/login                                    # Login (guest)
/register                                 # Registro (guest)
/                                         # Dashboard (auth)
/empresas                                 # Lista de empresas (auth)
/empresas/crear                           # Crear empresa (auth)
/empresas/:id                             # Detalle empresa (auth)
/empresas/:id/editar                      # Editar empresa (auth)
/empresas/:companyId/plantilla            # Editor de plantilla (auth)
/empresas/:companyId/tarjetas/crear       # Crear tarjeta (auth)
/empresas/:companyId/tarjetas/:cardId/editar  # Editar tarjeta (auth)
/:companySlug                             # Vista pública empresa
/:companySlug/:cardSlug                   # Vista pública tarjeta
```

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
- Componentes Vue en **Options API**
- Sin Vuex/Pinia — estado reactivo simple con `reactive()` + `computed()`
- Old-style Kernel.php (no migrado al patrón Laravel 11+ bootstrap/app.php)
- Iconos: Font Awesome (admin) + Bootstrap Icons (plantillas)

## Archivos de referencia (legacy)

La carpeta `ref/` contiene código del proyecto anterior para referencia:
- `template.txt` - Template Blade de tarjeta digital (base para TemplateModern.vue)
- `01-EmpresasController.txt` - Controlador legacy de empresas
- `04-SettingsController.txt` - Controlador legacy con ~60 campos de configuración
- `06-TarjetasController.txt` - Controlador legacy de tarjetas

## Notas técnicas importantes

### Servicios Vue - Métodos disponibles

```javascript
// companyService.js
companyService.all()           // GET /companies
companyService.get(id)         // GET /companies/{id}  ← usar este, NO .show()
companyService.store(formData) // POST /companies
companyService.update(id, formData) // PUT /companies/{id}
companyService.destroy(id)     // DELETE /companies/{id}

// settingService.js
settingService.getTemplates()        // GET /templates
settingService.getSchema(name)       // GET /templates/{name}/schema
settingService.getSettings(companyId) // GET /companies/{id}/settings
settingService.updateSettings(companyId, data) // PUT /companies/{id}/settings
settingService.resetSettings(companyId) // POST /companies/{id}/settings/reset
```

### Relaciones Eloquent - Cargar correctamente

```php
// INCORRECTO - $this->relation no carga automáticamente
if (!$this->settings) { ... }

// CORRECTO - usar query builder
$settings = $this->settings()->first();
if (!$settings) { ... }
```

### Comparaciones de IDs en controladores

```php
// Usar casting para evitar problemas string vs integer
if ((int) $company->user_id !== (int) Auth::id()) {
    return response()->json(['message' => 'No autorizado'], 403);
}
```

### CSS en Vue - No usar expresiones condicionales

```css
/* INCORRECTO - sintaxis inválida */
box-shadow: var(--photo-sombra) == true ? 2px 2px 10px #666 : none;

/* CORRECTO - usar clases dinámicas en el template */
```

```html
<img :class="{ 'with-shadow': customization?.photo?.sombra }">
```

```css
.profile-photo.with-shadow {
    box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.2);
}
```

### Limpiar cachés después de cambios en Laravel

```bash
php artisan config:clear && php artisan cache:clear && php artisan route:clear
```

## Próximos pasos sugeridos

1. Completar plantillas Classic y Minimal
2. Agregar campos adicionales a Company (redes sociales, dirección, teléfono, web)
3. Implementar vista pública CardPublic.vue con plantillas dinámicas
4. Agregar galería de fotos por empresa
5. Exportar/importar configuraciones de plantilla

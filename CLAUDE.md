# MuyLocal

Plataforma para generar tarjetas de presentacion digitales (minisitios) para empresas y sus empleados. Laravel 12 + Vue 3 SPA.

## Stack

- **Backend:** Laravel 12, PHP 8.2+, MySQL
- **Frontend:** Vue 3 SPA (Options API), Vue Router 4, Vite 4, Bootstrap 5, Bootstrap Icons
- **Auth:** Sanctum (API tokens), Spatie Laravel Permission (RBAC)
- **Otros:** Cloudinary (imagenes), vue-advanced-cropper (recorte de imagenes)

## Estructura del proyecto

```
app/
├── Http/Controllers/
│   ├── AuthController.php           # Login, register, logout, me
│   ├── CompanyController.php        # CRUD empresas
│   ├── CompanySettingController.php # API configuracion plantillas
│   ├── CardController.php           # CRUD tarjetas (anidado bajo empresa)
│   ├── ServiceController.php        # CRUD servicios (anidado bajo empresa)
│   ├── ProductController.php        # CRUD productos (anidado bajo empresa)
│   └── PublicCardController.php     # Vistas publicas de tarjetas
├── Http/Requests/
│   ├── StoreCompanyRequest.php      # Validacion crear empresa
│   ├── UpdateCompanyRequest.php     # Validacion actualizar empresa
│   ├── StoreCardRequest.php         # Validacion crear/actualizar tarjeta
│   ├── UpdateCardRequest.php        # Validacion actualizar tarjeta
│   ├── StoreProductRequest.php      # Validacion crear/actualizar producto
│   └── StoreServiceRequest.php      # Validacion crear/actualizar servicio
├── Models/
│   ├── User.php                     # HasApiTokens, HasRoles, belongsTo(Company)
│   ├── Company.php                  # hasMany(Card, Service, Product), hasOne(CompanySetting)
│   ├── CompanySetting.php           # Configuracion de plantilla (JSON)
│   ├── Card.php                     # Tarjeta de presentacion individual
│   ├── Service.php                  # Servicios de la empresa
│   └── Product.php                  # Productos de la empresa
├── Services/
│   └── CloudinaryService.php        # Upload/destroy de imagenes en Cloudinary

config/
├── templates.php                    # Schemas de plantillas disponibles

resources/
├── js/
│   ├── app.js                       # Entry point Vue
│   ├── services/
│   │   ├── api.js                   # Cliente axios con auth interceptors
│   │   ├── companyService.js        # API empresas
│   │   ├── cardService.js           # API tarjetas
│   │   ├── productService.js        # API productos
│   │   ├── serviceService.js        # API servicios
│   │   ├── settingService.js        # API configuracion plantillas
│   │   └── publicCardService.js     # API vistas publicas
│   ├── stores/
│   │   └── auth.js                  # Estado reactivo de autenticacion
│   ├── components/
│   │   ├── Header.vue, Sidebar.vue, Footer.vue
│   │   ├── AdminApp.vue, PublicApp.vue
│   │   └── templates/               # Sistema de plantillas
│   │       ├── LivePreview.vue      # Contenedor que renderiza plantillas
│   │       ├── TemplateModern.vue   # Plantilla moderna (~45 controles)
│   │       ├── TemplateClassic.vue  # Plantilla clasica (serif elegante)
│   │       ├── TemplateMinimal.vue  # Plantilla minimalista (B&N opcional)
│   │       ├── TemplateCreative.vue # Plantilla creativa (glassmorphism)
│   │       ├── TemplateCyber.vue    # Plantilla cyber (neon terminal)
│   │       └── TemplateVibrant.vue  # Plantilla vibrante (Bento Box)
│   └── views/
│       ├── company/                 # CompanyIndex, CompanyCreate, CompanyShow, CompanyEdit
│       ├── card/                    # CardCreate, CardEdit (con vue-advanced-cropper 1:1)
│       ├── product/                 # ProductCreate, ProductEdit (con cropper 1:1, 2:1, 3:1)
│       ├── service/                 # ServiceCreate, ServiceEdit (con cropper 1:1, 2:1, 3:1)
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
- `companies` - Empresas (name, slug, logo_path, logo_public_id, address, web, my_business, facebook, instagram, twitter, youtube)
- `company_settings` - Configuracion de plantilla por empresa (template_name, customization JSON)
- `cards` - Tarjetas de presentacion (first_name, last_name, slug, job_title, photo_path, email, mobile_phone, whatsapp, linkedin, whatsapp_message, description, is_active)
- `services` - Servicios de empresa (name, description, image_path, order, is_active)
- `products` - Productos de empresa (name, description, price, discount, comment, image_path, order, is_active)

### Tablas de soporte
- `company_types`, `industries`, `provinces`, `cities` - Catalogos
- `permissions`, `roles`, `model_has_*`, `role_has_permissions` - Spatie Permission
- `personal_access_tokens` - Sanctum
- `sessions`, `password_resets`, `failed_jobs` - Laravel

## Sistema de Plantillas Dinamicas

### Arquitectura

El sistema permite multiples plantillas con personalizacion visual completa via JSON:

1. **CompanySetting** (modelo): Almacena `template_name` y `customization` (JSON) por empresa
2. **config/templates.php**: Define plantillas disponibles y sus schemas de configuracion
3. **TemplateEditor.vue**: Editor visual con controles generados dinamicamente
4. **LivePreview.vue**: Contenedor que renderiza la plantilla seleccionada
5. **Template*.vue**: Plantillas que usan **computed styles inline** (NO CSS variables)

### Plantillas Disponibles

| Plantilla | Archivo | Caracteristicas | Complejidad |
|-----------|---------|-----------------|-------------|
| Modern | TemplateModern.vue | Acordeon, patrones, control granular | Alta (~45) |
| Classic | TemplateClassic.vue | Serif elegante, bordes dobles | Baja (~10) |
| Minimal | TemplateMinimal.vue | Ultra-limpio, filtro B&N | Baja (~10) |
| Creative | TemplateCreative.vue | Glassmorphism, luces ambientales | Media (~15) |
| Cyber | TemplateCyber.vue | Terminal, neon, cursor animado | Media (~10) |
| Vibrant | TemplateVibrant.vue | Bento Box, gradiente animado | Media (~12) |

### API de Settings

```
GET    /api/templates                         # Lista plantillas disponibles
GET    /api/templates/{name}/schema           # Schema de una plantilla
GET    /api/companies/{id}/settings           # Configuracion de empresa
PUT    /api/companies/{id}/settings           # Actualizar configuracion
POST   /api/companies/{id}/settings/reset     # Restablecer a defaults
```

### Estilos en Plantillas (IMPORTANTE)

**Las plantillas usan computed styles inline, NO CSS variables heredadas.**

```javascript
// CORRECTO - Computed styles inline
computed: {
    avatarStyle() {
        const avatar = this.customization?.avatar || {}
        return {
            width: `${avatar.tamano ?? 120}px`,
            height: `${avatar.tamano ?? 120}px`,
            borderRadius: `${avatar.radio ?? 50}%`,
        }
    }
}

// En template
<img :style="avatarStyle" ...>
```

## Rutas Vue

```
/login                                        # Login (guest)
/register                                     # Registro (guest)
/                                             # Dashboard (auth)
/empresas                                     # Lista de empresas (auth)
/empresas/crear                               # Crear empresa (auth)
/empresas/:id                                 # Detalle empresa (auth)
/empresas/:id/editar                          # Editar empresa (auth)
/empresas/:companyId/plantilla                # Editor de plantilla (auth)
/empresas/:companyId/tarjetas/crear           # Crear tarjeta (auth)
/empresas/:companyId/tarjetas/:cardId/editar  # Editar tarjeta (auth)
/empresas/:companyId/productos/crear          # Crear producto (auth)
/empresas/:companyId/productos/:productId/editar  # Editar producto (auth)
/empresas/:companyId/servicios/crear          # Crear servicio (auth)
/empresas/:companyId/servicios/:serviceId/editar  # Editar servicio (auth)
/:companySlug                                 # Vista publica empresa
/:companySlug/:cardSlug                       # Vista publica tarjeta
```

## API Endpoints

### Empresas
```
GET    /api/companies                # Lista empresas del usuario
POST   /api/companies                # Crear empresa
GET    /api/companies/{id}           # Detalle empresa (con cards, services, products)
PUT    /api/companies/{id}           # Actualizar empresa
DELETE /api/companies/{id}           # Eliminar empresa
```

### Tarjetas (anidadas bajo empresa)
```
GET    /api/companies/{company}/cards           # Lista tarjetas
POST   /api/companies/{company}/cards           # Crear tarjeta
GET    /api/companies/{company}/cards/{card}    # Detalle tarjeta
PUT    /api/companies/{company}/cards/{card}    # Actualizar tarjeta
DELETE /api/companies/{company}/cards/{card}    # Eliminar tarjeta
```

### Productos (anidados bajo empresa)
```
GET    /api/companies/{company}/products              # Lista productos
POST   /api/companies/{company}/products              # Crear producto
GET    /api/companies/{company}/products/{product}    # Detalle producto
PUT    /api/companies/{company}/products/{product}    # Actualizar producto
DELETE /api/companies/{company}/products/{product}    # Eliminar producto
```

### Servicios (anidados bajo empresa)
```
GET    /api/companies/{company}/services              # Lista servicios
POST   /api/companies/{company}/services              # Crear servicio
GET    /api/companies/{company}/services/{service}    # Detalle servicio
PUT    /api/companies/{company}/services/{service}    # Actualizar servicio
DELETE /api/companies/{company}/services/{service}    # Eliminar servicio
```

## Cloudinary - Estructura de Carpetas

```
muylocal/                    # Carpeta base (segun APP_ENV)
├── companies/
│   ├── {slug}/logo/         # Logos de empresas
│   ├── cards/               # Fotos de perfil de tarjetas
│   ├── products/            # Imagenes de productos
│   └── services/            # Imagenes de servicios
```

## vue-advanced-cropper

Integrado para recorte de imagenes antes de subir:

### Tarjetas (CardCreate, CardEdit)
- Proporcion fija: **1:1** (cuadrada para fotos de perfil)

### Productos y Servicios (ProductCreate, ProductEdit, ServiceCreate, ServiceEdit)
- Proporciones seleccionables: **1:1**, **2:1**, **3:1**

```javascript
// Ejemplo de uso
import { Cropper } from 'vue-advanced-cropper';
import 'vue-advanced-cropper/dist/style.css';

// En data()
ratios: [
    { label: '1:1', value: 1 },
    { label: '2:1', value: 2 },
    { label: '3:1', value: 3 },
],
selectedRatio: 1,

// En template
<Cropper
    ref="cropper"
    :src="cropperSrc"
    :stencil-props="{ aspectRatio: selectedRatio }"
/>
```

## Servicios Vue - Metodos disponibles

```javascript
// companyService.js
companyService.all()           // GET /companies
companyService.get(id)         // GET /companies/{id}
companyService.store(formData) // POST /companies
companyService.update(id, formData) // PUT /companies/{id}
companyService.destroy(id)     // DELETE /companies/{id}

// cardService.js
cardService.all(companyId)                  // GET /companies/{id}/cards
cardService.get(companyId, cardId)          // GET /companies/{id}/cards/{cardId}
cardService.store(companyId, formData)      // POST /companies/{id}/cards
cardService.update(companyId, cardId, formData) // PUT /companies/{id}/cards/{cardId}
cardService.destroy(companyId, cardId)      // DELETE /companies/{id}/cards/{cardId}

// productService.js
productService.all(companyId)                     // GET /companies/{id}/products
productService.get(companyId, productId)          // GET /companies/{id}/products/{productId}
productService.store(companyId, formData)         // POST /companies/{id}/products
productService.update(companyId, productId, formData) // PUT
productService.destroy(companyId, productId)      // DELETE

// serviceService.js
serviceService.all(companyId)                     // GET /companies/{id}/services
serviceService.get(companyId, serviceId)          // GET /companies/{id}/services/{serviceId}
serviceService.store(companyId, formData)         // POST /companies/{id}/services
serviceService.update(companyId, serviceId, formData) // PUT
serviceService.destroy(companyId, serviceId)      // DELETE

// settingService.js
settingService.getTemplates()        // GET /templates
settingService.getSchema(name)       // GET /templates/{name}/schema
settingService.getSettings(companyId) // GET /companies/{id}/settings
settingService.updateSettings(companyId, data) // PUT /companies/{id}/settings
settingService.resetSettings(companyId) // POST /companies/{id}/settings/reset
```

## Autenticacion

- Token-based via API: `/api/login`, `/api/register`, `/api/me`, `/api/logout`
- Token se guarda en `localStorage` como `auth_token`
- Axios interceptor agrega header `Authorization: Bearer` automaticamente
- Vue Router guards protegen rutas con `meta.requiresAuth` / `meta.guest`
- Fortify esta instalado pero **desactivado** - no usar sus rutas

## Comandos

```bash
npm run dev          # Vite dev server
npm run build        # Build produccion
php artisan serve    # Laravel dev server
php artisan migrate  # Ejecutar migraciones

# Limpiar caches despues de cambios en config/
php artisan config:clear && php artisan cache:clear && php artisan route:clear
```

## Convenciones

- Idioma de la interfaz: **Espanol**
- API responses en JSON, errores de validacion en formato Laravel estandar (422)
- Rutas API bajo `/api/` con middleware `auth:sanctum` para protegidas
- Componentes Vue en **Options API**
- Sin Vuex/Pinia - estado reactivo simple con `reactive()` + `computed()`
- Old-style Kernel.php (no migrado al patron Laravel 11+ bootstrap/app.php)
- Iconos: Font Awesome (admin) + Bootstrap Icons (plantillas)
- Formularios en dos columnas para aprovechar espacio

## Archivos de referencia (legacy)

La carpeta `ref/` contiene codigo del proyecto anterior para referencia:
- `template.txt` - Template Blade de tarjeta digital (base para TemplateModern.vue)
- `01-EmpresasController.txt` - Controlador legacy de empresas
- `04-SettingsController.txt` - Controlador legacy con ~60 campos de configuracion
- `06-TarjetasController.txt` - Controlador legacy de tarjetas

## Notas tecnicas importantes

### Relaciones Eloquent - Cargar correctamente

```php
// INCORRECTO - $this->relation no carga automaticamente
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

### FormData con metodo PUT (spoofing)

```javascript
// Para updates con archivos, usar POST con _method
update(companyId, productId, formData) {
    formData.append('_method', 'PUT');
    return api.post(`/companies/${companyId}/products/${productId}`, formData, {
        headers: { 'Content-Type': 'multipart/form-data' },
    });
}
```

### CSS en Vue - No usar expresiones condicionales

```html
<!-- CORRECTO - usar clases dinamicas -->
<img :class="{ 'with-shadow': customization?.photo?.sombra }">
```

```css
.profile-photo.with-shadow {
    box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.2);
}
```

## Archivos de Memoria

El directorio `.claude/` contiene documentacion de trabajo:

```
.claude/
├── templates-inventory.md   # Inventario completo de plantillas
├── ux-improvements.md       # Mejoras de UX identificadas
├── session-history.md       # Historial de trabajo realizado
└── crud-implementation.md   # Detalles del CRUD de tarjetas/productos/servicios
```

Tambien existe `indications.txt` en la raiz con guia completa para agregar plantillas.

## Estado Actual del Proyecto

### Completado
- [x] Sistema de plantillas dinamicas (6 plantillas)
- [x] CRUD completo de empresas con Cloudinary
- [x] CRUD completo de tarjetas con vue-advanced-cropper (1:1)
- [x] CRUD completo de productos con vue-advanced-cropper (1:1, 2:1, 3:1)
- [x] CRUD completo de servicios con vue-advanced-cropper (1:1, 2:1, 3:1)
- [x] Editor de plantillas con live preview
- [x] Vista publica de tarjetas con plantillas dinamicas
- [x] Formularios en dos columnas
- [x] Footer solido en panel admin

### Proximos pasos sugeridos
- [ ] Agregar galeria de fotos por empresa
- [ ] Exportar/importar configuraciones de plantilla
- [ ] Sistema de presets/temas por plantilla
- [ ] Preview en selector de plantillas
- [ ] Estandarizar nomenclatura de secciones en schemas

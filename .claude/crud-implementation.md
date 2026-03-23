# Implementacion CRUD - MuyLocal

## Resumen de Entidades

| Entidad | Controlador | Modelo | Form Request | Service JS | Vistas Vue |
|---------|-------------|--------|--------------|------------|------------|
| Company | CompanyController | Company | Store/Update | companyService | Index, Create, Show, Edit |
| Card | CardController | Card | Store/Update | cardService | Create, Edit |
| Product | ProductController | Product | Store | productService | Create, Edit |
| Service | ServiceController | Service | Store | serviceService | Create, Edit |

---

## Tarjetas (Cards)

### Campos de Base de Datos
```php
Schema::create('cards', function (Blueprint $table) {
    $table->id();
    $table->foreignId('company_id')->constrained()->cascadeOnDelete();
    $table->string('first_name', 80);
    $table->string('last_name', 80);
    $table->string('slug', 80);
    $table->string('job_title', 120)->nullable();
    $table->string('photo_path')->nullable();
    $table->string('mobile_phone', 30)->nullable();
    $table->string('whatsapp', 30)->nullable();
    $table->string('email', 150)->nullable();
    $table->string('linkedin')->nullable();
    $table->string('whatsapp_message', 500)->nullable();
    $table->text('description')->nullable();
    $table->boolean('is_active')->default(true);
    $table->timestamps();

    $table->unique(['company_id', 'slug']);
});
```

### Modelo (Card.php)
```php
protected $fillable = [
    'company_id', 'first_name', 'last_name', 'slug', 'job_title',
    'photo_path', 'mobile_phone', 'whatsapp', 'email', 'linkedin',
    'whatsapp_message', 'description', 'is_active',
];

protected $casts = [
    'is_active' => 'boolean',
];
```

### Validacion (StoreCardRequest.php)
```php
return [
    'first_name'   => ['required', 'string', 'max:80'],
    'last_name'    => ['required', 'string', 'max:80'],
    'slug'         => ['required', 'string', 'max:80', 'alpha_dash',
                      Rule::unique('cards', 'slug')->where('company_id', $companyId)],
    'job_title'    => ['nullable', 'string', 'max:120'],
    'photo'        => ['nullable', 'image', 'max:5120'],
    'mobile_phone' => ['nullable', 'string', 'max:30'],
    'whatsapp'     => ['nullable', 'string', 'max:30'],
    'email'        => ['nullable', 'email', 'max:150'],
    'linkedin'     => ['nullable', 'url', 'max:255'],
    'whatsapp_message' => ['nullable', 'string', 'max:500'],
    'description'  => ['nullable', 'string', 'max:1000'],
    'is_active'    => ['nullable', 'boolean'],
];
```

### Cropper Vue (1:1 fijo)
```javascript
// En CardCreate.vue / CardEdit.vue
<Cropper
    ref="cropper"
    :src="cropperSrc"
    :stencil-props="{ aspectRatio: 1 }"
    class="cropper"
/>
```

---

## Productos (Products)

### Campos de Base de Datos
```php
Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->foreignId('company_id')->constrained()->cascadeOnDelete();
    $table->string('name', 120);
    $table->string('image_path')->nullable();
    $table->decimal('price', 10, 2);
    $table->decimal('discount', 5, 2)->nullable();
    $table->string('comment', 120)->nullable();
    $table->text('description')->nullable();
    $table->unsignedInteger('order')->default(0);
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

### Modelo (Product.php)
```php
protected $fillable = [
    'company_id', 'name', 'image_path', 'price', 'discount',
    'comment', 'description', 'order', 'is_active',
];

protected $casts = [
    'price'     => 'decimal:2',
    'discount'  => 'decimal:2',
    'is_active' => 'boolean',
    'order'     => 'integer',
];

// Accessor para precio final
public function getFinalPriceAttribute(): float
{
    return round($this->price * (1 - $this->discount / 100), 2);
}
```

### Validacion (StoreProductRequest.php)
```php
return [
    'name'        => ['required', 'string', 'max:120'],
    'image'       => ['nullable', 'image', 'max:5120'],
    'price'       => ['required', 'numeric', 'min:0'],
    'discount'    => ['nullable', 'numeric', 'min:0', 'max:100'],
    'comment'     => ['nullable', 'string', 'max:120'],
    'description' => ['nullable', 'string', 'max:1000'],
    'order'       => ['nullable', 'integer', 'min:0'],
    'is_active'   => ['nullable', 'boolean'],
];
```

### Cropper Vue (1:1, 2:1, 3:1)
```javascript
// En ProductCreate.vue / ProductEdit.vue
data() {
    return {
        selectedRatio: 1,
        ratios: [
            { label: '1:1', value: 1 },
            { label: '2:1', value: 2 },
            { label: '3:1', value: 3 },
        ],
    }
}

// Template
<Cropper
    ref="cropper"
    :src="cropperSrc"
    :stencil-props="{ aspectRatio: selectedRatio }"
    class="cropper"
/>
```

---

## Servicios (Services)

### Campos de Base de Datos
```php
Schema::create('services', function (Blueprint $table) {
    $table->id();
    $table->foreignId('company_id')->constrained()->cascadeOnDelete();
    $table->string('name', 120);
    $table->string('image_path')->nullable();
    $table->text('description')->nullable();
    $table->unsignedInteger('order')->default(0);
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

### Modelo (Service.php)
```php
protected $fillable = [
    'company_id', 'name', 'image_path', 'description', 'order', 'is_active',
];

protected $casts = [
    'is_active' => 'boolean',
    'order'     => 'integer',
];
```

### Validacion (StoreServiceRequest.php)
```php
return [
    'name'        => ['required', 'string', 'max:120'],
    'image'       => ['nullable', 'image', 'max:5120'],
    'description' => ['nullable', 'string', 'max:1000'],
    'order'       => ['nullable', 'integer', 'min:0'],
    'is_active'   => ['nullable', 'boolean'],
];
```

---

## Patron de Controlador con Cloudinary

```php
class ProductController extends Controller
{
    public function __construct(private CloudinaryService $cloudinary) {}

    public function store(StoreProductRequest $request, Company $company): JsonResponse
    {
        $this->authorizeOwner($request, $company);

        $data = $request->validated();
        $data['company_id'] = $company->id;

        if ($request->hasFile('image')) {
            $uploaded = $this->cloudinary->upload(
                $request->file('image'),
                'companies/products'  // Ruta descriptiva
            );
            $data['image_path'] = $uploaded['url'];
        }

        $product = Product::create($data);

        return response()->json($product, 201);
    }

    public function update(StoreProductRequest $request, Company $company, Product $product): JsonResponse
    {
        $this->authorizeOwner($request, $company);
        abort_if($product->company_id !== $company->id, 404);

        $data = $request->validated();

        if ($request->hasFile('image')) {
            // Eliminar imagen anterior
            if ($product->image_path) {
                $this->cloudinary->destroy($product->image_path);
            }
            // Subir nueva
            $uploaded = $this->cloudinary->upload(
                $request->file('image'),
                'companies/products'
            );
            $data['image_path'] = $uploaded['url'];
        }

        $product->update($data);

        return response()->json($product->fresh());
    }

    public function destroy(Request $request, Company $company, Product $product): JsonResponse
    {
        $this->authorizeOwner($request, $company);
        abort_if($product->company_id !== $company->id, 404);

        if ($product->image_path) {
            $this->cloudinary->destroy($product->image_path);
        }

        $product->delete();

        return response()->json(null, 204);
    }

    private function authorizeOwner(Request $request, Company $company): void
    {
        abort_if($company->user_id !== $request->user()->id, 403, 'No autorizado.');
    }
}
```

---

## Patron de Service JS

```javascript
import api from './api.js';

export default {
    all(companyId) {
        return api.get(`/companies/${companyId}/products`);
    },

    get(companyId, productId) {
        return api.get(`/companies/${companyId}/products/${productId}`);
    },

    store(companyId, formData) {
        return api.post(`/companies/${companyId}/products`, formData, {
            headers: { 'Content-Type': 'multipart/form-data' },
        });
    },

    update(companyId, productId, formData) {
        // PUT spoofing para FormData con archivos
        formData.append('_method', 'PUT');
        return api.post(`/companies/${companyId}/products/${productId}`, formData, {
            headers: { 'Content-Type': 'multipart/form-data' },
        });
    },

    destroy(companyId, productId) {
        return api.delete(`/companies/${companyId}/products/${productId}`);
    },
};
```

---

## Patron de Vista Vue con Cropper

```vue
<template>
  <form @submit.prevent="submit">
    <!-- Campos del formulario -->
    <div class="mb-3">
      <label class="form-label">Imagen</label>
      <input ref="fileInput" type="file" class="form-control"
             accept="image/*" @change="onFileSelected" />
      <div v-if="imagePreview" class="mt-2 d-flex align-items-center gap-2">
        <img :src="imagePreview" class="rounded" style="height: 100px" />
        <button type="button" class="btn btn-sm btn-outline-secondary"
                @click="openCropper">
          <i class="fa fa-crop me-1"></i> Recortar
        </button>
      </div>
    </div>
  </form>

  <!-- Modal Cropper -->
  <div v-if="cropperOpen" class="cropper-overlay">
    <div class="cropper-dialog">
      <div class="cropper-dialog__header">
        <span class="fw-semibold">Recortar imagen</span>
        <button type="button" class="btn-close" @click="cancelCrop"></button>
      </div>

      <!-- Selector de ratio (opcional) -->
      <div class="mb-3 d-flex gap-2 flex-wrap">
        <button v-for="r in ratios" :key="r.label" type="button"
          :class="['btn btn-sm', selectedRatio === r.value ? 'btn-primary' : 'btn-outline-secondary']"
          @click="selectedRatio = r.value">
          {{ r.label }}
        </button>
      </div>

      <div class="cropper-wrapper">
        <Cropper ref="cropper" :src="cropperSrc"
          :stencil-props="{ aspectRatio: selectedRatio }" class="cropper" />
      </div>

      <div class="d-flex gap-2 mt-3">
        <button type="button" class="btn btn-primary" @click="confirmCrop">
          <i class="fa fa-check me-1"></i> Aplicar recorte
        </button>
        <button type="button" class="btn btn-outline-secondary" @click="cancelCrop">
          Cancelar
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import { Cropper } from 'vue-advanced-cropper';
import 'vue-advanced-cropper/dist/style.css';

export default {
  components: { Cropper },

  data() {
    return {
      imagePreview: null,
      imageFile: null,
      cropperOpen: false,
      cropperSrc: null,
      selectedRatio: 1,
      ratios: [
        { label: '1:1', value: 1 },
        { label: '2:1', value: 2 },
        { label: '3:1', value: 3 },
      ],
    };
  },

  methods: {
    onFileSelected(e) {
      const file = e.target.files[0];
      if (!file) return;
      this.cropperSrc = URL.createObjectURL(file);
      this.cropperOpen = true;
    },

    openCropper() {
      this.cropperSrc = this.imagePreview;
      this.cropperOpen = true;
    },

    confirmCrop() {
      const { canvas } = this.$refs.cropper.getResult();
      canvas.toBlob((blob) => {
        this.imageFile = new File([blob], 'image.png', { type: 'image/png' });
        if (this.imagePreview) URL.revokeObjectURL(this.imagePreview);
        this.imagePreview = URL.createObjectURL(blob);
        this.cropperOpen = false;
      }, 'image/png');
    },

    cancelCrop() {
      this.cropperOpen = false;
      if (!this.imageFile) {
        this.cropperSrc = null;
        this.$refs.fileInput.value = '';
      }
    },
  },
};
</script>

<style scoped>
.cropper-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.65);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1060;
}

.cropper-dialog {
  background: #fff;
  border-radius: 0.5rem;
  padding: 1.5rem;
  width: min(600px, 95vw);
  max-height: 90vh;
  overflow-y: auto;
}

.cropper-wrapper {
  height: 380px;
  background: #1a1a1a;
  border-radius: 0.375rem;
  overflow: hidden;
}

.cropper {
  height: 100%;
}
</style>
```

---

## Rutas API (routes/api.php)

```php
Route::middleware('auth:sanctum')->group(function () {
    // Tarjetas (CRUD completo)
    Route::apiResource('companies.cards', CardController::class);

    // Servicios (CRUD completo)
    Route::apiResource('companies.services', ServiceController::class);

    // Productos (CRUD completo)
    Route::apiResource('companies.products', ProductController::class);
});
```

---

## Rutas Vue (router/index.js)

```javascript
// Tarjetas
{ path: '/empresas/:companyId/tarjetas/crear', name: 'cards.create', component: CardCreate },
{ path: '/empresas/:companyId/tarjetas/:cardId/editar', name: 'cards.edit', component: CardEdit },

// Productos
{ path: '/empresas/:companyId/productos/crear', name: 'products.create', component: ProductCreate },
{ path: '/empresas/:companyId/productos/:productId/editar', name: 'products.edit', component: ProductEdit },

// Servicios
{ path: '/empresas/:companyId/servicios/crear', name: 'services.create', component: ServiceCreate },
{ path: '/empresas/:companyId/servicios/:serviceId/editar', name: 'services.edit', component: ServiceEdit },
```

---

*Ultima actualizacion: Marzo 2026*

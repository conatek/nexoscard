<template>
  <div class="app-page-title">
    <div class="page-title-wrapper">
      <div class="page-title-heading">
        <div class="page-title-icon"><i class="fa fa-concierge-bell icon-gradient bg-mean-fruit"></i></div>
        <div>Nuevo servicio<div class="page-title-subheading">{{ companyName }}</div></div>
      </div>
      <div class="page-title-actions">
        <router-link :to="{ name: 'companies.show', params: { id: $route.params.companyId } }"
                     class="btn btn-outline-secondary btn-sm">
          <i class="fa fa-arrow-left me-1"></i> Volver
        </router-link>
      </div>
    </div>
  </div>

  <form @submit.prevent="submit">
    <div class="row">
      <!-- Columna izquierda: Informacion del servicio -->
      <div class="col-lg-6">
        <div class="card mb-4">
          <div class="card-header fw-semibold">
            <i class="fa fa-concierge-bell me-2 text-primary"></i>Informacion del servicio
          </div>
          <div class="card-body">
            <div class="mb-3">
              <label class="form-label">Nombre *</label>
              <input v-model="form.name" type="text" class="form-control" :class="errorClass('name')" />
              <div class="invalid-feedback">{{ errors.name?.[0] }}</div>
            </div>

            <div class="mb-3">
              <label class="form-label">Descripcion</label>
              <textarea v-model="form.description" class="form-control" rows="4"
                        placeholder="Descripcion del servicio..."></textarea>
            </div>

            <div class="mb-3">
              <label class="form-label">Imagen del servicio</label>
              <input ref="fileInput" type="file" class="form-control" accept="image/*" @change="onFileSelected" />
              <div v-if="imagePreview" class="mt-2 d-flex align-items-center gap-2">
                <img :src="imagePreview" class="rounded border"
                     style="height: 100px; width: auto; max-width: 200px; object-fit: cover" />
                <button type="button" class="btn btn-sm btn-outline-secondary" @click="openCropper">
                  <i class="fa fa-crop me-1"></i> Recortar
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Columna derecha: Opciones -->
      <div class="col-lg-6">
        <div class="card mb-4">
          <div class="card-header fw-semibold">
            <i class="fa fa-cog me-2 text-primary"></i>Opciones
          </div>
          <div class="card-body">
            <div class="row g-3 mb-3">
              <div class="col-6">
                <label class="form-label">Orden</label>
                <input v-model="form.order" type="number" min="0" class="form-control" placeholder="0" />
                <div class="form-text">Menor = aparece primero</div>
              </div>
              <div class="col-6 d-flex align-items-end">
                <div class="form-check form-switch">
                  <input class="form-check-input" type="checkbox" v-model="form.is_active" id="isActive" />
                  <label class="form-check-label" for="isActive">Servicio activo</label>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Botones de accion -->
        <div v-if="generalError" class="alert alert-danger">{{ generalError }}</div>

        <div class="d-flex gap-2 justify-content-end">
          <router-link :to="{ name: 'companies.show', params: { id: $route.params.companyId } }"
                       class="btn btn-outline-secondary">Cancelar</router-link>
          <button type="submit" class="btn btn-primary" :disabled="saving">
            <span v-if="saving" class="spinner-border spinner-border-sm me-1"></span>
            <i v-else class="fa fa-check me-1"></i>
            Crear servicio
          </button>
        </div>
      </div>
    </div>
  </form>

  <!-- Modal de recorte -->
  <div v-if="cropperOpen" class="cropper-overlay">
    <div class="cropper-dialog">
      <div class="cropper-dialog__header">
        <span class="fw-semibold">Recortar imagen del servicio</span>
        <button type="button" class="btn-close" @click="cancelCrop"></button>
      </div>

      <div class="mb-3 d-flex gap-2 flex-wrap">
        <button
          v-for="r in ratios"
          :key="r.label"
          type="button"
          :class="['btn btn-sm', selectedRatio === r.value ? 'btn-primary' : 'btn-outline-secondary']"
          @click="selectedRatio = r.value"
        >
          {{ r.label }}
        </button>
      </div>

      <div class="cropper-wrapper">
        <Cropper
          ref="cropper"
          :src="cropperSrc"
          :stencil-props="{ aspectRatio: selectedRatio }"
          class="cropper"
        />
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
import serviceService from '@/services/serviceService.js';
import companyService from '@/services/companyService.js';

export default {
  name: 'ServiceCreate',

  components: { Cropper },

  data() {
    return {
      companyName: '',
      saving: false,
      errors: {},
      generalError: null,
      imagePreview: null,
      imageFile: null,
      // Cropper
      cropperOpen: false,
      cropperSrc: null,
      selectedRatio: 1,
      ratios: [
        { label: '1:1', value: 1 },
        { label: '2:1', value: 2 },
        { label: '3:1', value: 3 },
      ],
      form: {
        name: '',
        description: '',
        order: 0,
        is_active: true,
      },
    };
  },

  async created() {
    const { data } = await companyService.get(this.$route.params.companyId);
    this.companyName = data.name;
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

    errorClass(field) {
      return this.errors[field] ? 'is-invalid' : '';
    },

    async submit() {
      this.saving = true;
      this.errors = {};
      this.generalError = null;

      const payload = new FormData();
      Object.entries(this.form).forEach(([k, v]) => {
        if (v !== null && v !== '') payload.append(k, v);
      });
      if (this.imageFile) payload.append('image', this.imageFile);

      try {
        await serviceService.store(this.$route.params.companyId, payload);
        this.$router.push({ name: 'companies.show', params: { id: this.$route.params.companyId } });
      } catch (err) {
        if (err.response?.status === 422) {
          this.errors = err.response.data.errors;
        } else {
          this.generalError = err.response?.data?.message || 'Error al crear el servicio.';
        }
      } finally {
        this.saving = false;
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

.cropper-dialog__header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
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

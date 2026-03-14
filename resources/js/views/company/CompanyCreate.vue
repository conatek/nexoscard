<template>
  <div class="app-page-title">
    <div class="page-title-wrapper">
      <div class="page-title-heading">
        <div class="page-title-icon"><i class="fa fa-building icon-gradient bg-mean-fruit"></i></div>
        <div>Nueva Empresa</div>
      </div>
      <div class="page-title-actions">
        <router-link :to="{ name: 'companies.index' }" class="btn btn-outline-secondary btn-sm">
          <i class="fa fa-arrow-left me-1"></i> Volver
        </router-link>
      </div>
    </div>
  </div>

  <div style="max-width: 540px">
    <form @submit.prevent="submit">
      <div class="card mb-4">
        <div class="card-header fw-semibold">Información general</div>
        <div class="card-body row g-3">

          <div class="col-12">
            <label class="form-label">Nombre de la empresa *</label>
            <input v-model="form.name" type="text" class="form-control" :class="errorClass('name')"
              @input="autoSlug" placeholder="Ej: Mi Empresa S.A." />
            <div class="invalid-feedback">{{ errors.name?.[0] }}</div>
          </div>

          <div class="col-12">
            <label class="form-label">Slug (URL pública) *</label>
            <div class="input-group">
              <span class="input-group-text text-muted small">dominio.com/</span>
              <input v-model="form.slug" type="text" class="form-control" :class="errorClass('slug')"
                @input="form.slug = slugify(form.slug)" />
            </div>
            <div class="invalid-feedback d-block" v-if="errors.slug">{{ errors.slug[0] }}</div>
            <div class="form-text">Solo letras minúsculas, números y guiones.</div>
          </div>

          <div class="col-12">
            <label class="form-label">Logotipo</label>
            <input ref="fileInput" type="file" class="form-control" accept="image/*" @change="onFileSelected" />
            <div v-if="logoPreview" class="mt-2 d-flex align-items-center gap-2">
              <img :src="logoPreview" class="rounded border"
                style="height: 72px; object-fit: contain; background:#f8f9fa; padding:4px" />
              <button type="button" class="btn btn-sm btn-outline-secondary" @click="openCropper">
                <i class="fa fa-crop me-1"></i> Recortar
              </button>
            </div>
          </div>

        </div>
      </div>

      <div v-if="generalError" class="alert alert-danger py-2 small">{{ generalError }}</div>

      <div class="d-grid gap-2">
        <button type="submit" class="btn btn-primary" :disabled="loading">
          <span v-if="loading" class="spinner-border spinner-border-sm me-1"></span>
          <i v-else class="fa fa-check me-1"></i>
          Crear empresa
        </button>
        <router-link :to="{ name: 'companies.index' }" class="btn btn-outline-secondary">
          Cancelar
        </router-link>
      </div>
    </form>
  </div>

  <!-- Modal de recorte -->
  <div v-if="cropperOpen" class="cropper-overlay">
    <div class="cropper-dialog">
      <div class="cropper-dialog__header">
        <span class="fw-semibold">Recortar logotipo</span>
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
import companyService from '@/services/companyService.js';

export default {
  name: 'CompanyCreate',

  components: { Cropper },

  data() {
    return {
      loading: false,
      errors: {},
      generalError: null,
      logoPreview: null,
      logoFile: null,
      slugManuallyEdited: false,
      form: {
        name: '',
        slug: '',
      },
      // Cropper
      cropperOpen: false,
      cropperSrc: null,
      selectedRatio: 1,
      ratios: [
        { label: '1:1', value: 1 },
        { label: '2:1', value: 2 },
        { label: '3:1', value: 3 },
        { label: '4:1', value: 4 },
      ],
    };
  },

  methods: {
    slugify(v) {
      return v.toLowerCase().replace(/\s+/g, '-').replace(/[^a-z0-9\-_]/g, '');
    },

    autoSlug() {
      if (!this.slugManuallyEdited) {
        this.form.slug = this.slugify(this.form.name);
      }
    },

    onFileSelected(e) {
      const file = e.target.files[0];
      if (!file) return;
      this.cropperSrc = URL.createObjectURL(file);
      this.cropperOpen = true;
    },

    openCropper() {
      this.cropperOpen = true;
    },

    confirmCrop() {
      const { canvas } = this.$refs.cropper.getResult();
      canvas.toBlob((blob) => {
        const filename = 'logo.png';
        this.logoFile = new File([blob], filename, { type: 'image/png' });
        if (this.logoPreview) URL.revokeObjectURL(this.logoPreview);
        this.logoPreview = URL.createObjectURL(blob);
        this.cropperOpen = false;
      }, 'image/png');
    },

    cancelCrop() {
      this.cropperOpen = false;
      if (!this.logoFile) {
        // Si no hay imagen confirmada aún, limpiar el input
        this.cropperSrc = null;
        this.$refs.fileInput.value = '';
      }
    },

    errorClass(field) {
      return this.errors[field] ? 'is-invalid' : '';
    },

    async submit() {
      this.loading = true;
      this.errors = {};
      this.generalError = null;

      const payload = new FormData();
      payload.append('name', this.form.name);
      payload.append('slug', this.form.slug);
      if (this.logoFile) payload.append('logo', this.logoFile, 'logo.png');

      try {
        const { data } = await companyService.store(payload);
        this.$router.push({ name: 'companies.show', params: { id: data.id } });
      } catch (err) {
        if (err.response?.status === 422) {
          this.errors = err.response.data.errors;
        } else {
          this.generalError = err.response?.data?.message || 'Error al crear la empresa.';
        }
      } finally {
        this.loading = false;
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
  width: min(680px, 95vw);
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

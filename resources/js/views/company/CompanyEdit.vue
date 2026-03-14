<template>
  <div v-if="loading" class="text-center py-5">
    <div class="spinner-border text-primary"></div>
  </div>

  <template v-else>
    <div class="app-page-title">
      <div class="page-title-wrapper">
        <div class="page-title-heading">
          <div class="page-title-icon"><i class="fa fa-edit icon-gradient bg-mean-fruit"></i></div>
          <div>Editar empresa<div class="page-title-subheading">{{ form.name }}</div></div>
        </div>
        <div class="page-title-actions">
          <router-link :to="{ name: 'companies.show', params: { id: $route.params.id } }"
                       class="btn btn-outline-secondary btn-sm">
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
              <label class="form-label">Nombre *</label>
              <input v-model="form.name" type="text" class="form-control" :class="errorClass('name')" />
              <div class="invalid-feedback">{{ errors.name?.[0] }}</div>
            </div>

            <div class="col-12">
              <label class="form-label">Slug *</label>
              <div class="input-group">
                <span class="input-group-text text-muted">dominio.com/</span>
                <input v-model="form.slug" type="text" class="form-control" :class="errorClass('slug')"
                  @input="form.slug = slugify(form.slug)" />
              </div>
              <div class="invalid-feedback d-block" v-if="errors.slug">{{ errors.slug[0] }}</div>
            </div>

            <div class="col-12">
              <label class="form-label">Logotipo</label>
              <input ref="fileInput" type="file" class="form-control" accept="image/*" @change="onFileSelected" />
              <div v-if="logoPreview || form.logo_path" class="mt-2 d-flex align-items-center gap-2">
                <img :src="logoPreview || form.logo_path" class="rounded border"
                     style="height:72px;width:auto;max-width:220px;object-fit:contain;background:#f8f9fa;padding:4px" />
                <button type="button" class="btn btn-sm btn-outline-secondary" @click="openCropper">
                  <i class="fa fa-crop me-1"></i> Recortar
                </button>
              </div>
            </div>

          </div>
        </div>

        <div v-if="generalError" class="alert alert-danger">{{ generalError }}</div>

        <div class="d-flex justify-content-end gap-2 mb-4">
          <router-link :to="{ name: 'companies.show', params: { id: $route.params.id } }"
                       class="btn btn-outline-secondary">Cancelar</router-link>
          <button type="submit" class="btn btn-primary" :disabled="saving">
            <span v-if="saving" class="spinner-border spinner-border-sm me-1"></span>
            Guardar cambios
          </button>
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
</template>

<script>
import { Cropper } from 'vue-advanced-cropper';
import 'vue-advanced-cropper/dist/style.css';
import companyService from '@/services/companyService.js';

export default {
  name: 'CompanyEdit',

  components: { Cropper },

  data() {
    return {
      loading: true,
      saving: false,
      errors: {},
      generalError: null,
      logoPreview: null,
      logoFile: null,
      form: { name: '', slug: '', logo_path: null },
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

  async created() {
    const { data } = await companyService.get(this.$route.params.id);
    this.form = { name: data.name, slug: data.slug, logo_path: data.logo_path };
    this.loading = false;
  },

  methods: {
    slugify(v) {
      return v.toLowerCase().replace(/\s+/g, '-').replace(/[^a-z0-9\-_]/g, '');
    },

    onFileSelected(e) {
      const file = e.target.files[0];
      if (!file) return;
      this.cropperSrc = URL.createObjectURL(file);
      this.cropperOpen = true;
    },

    openCropper() {
      this.cropperSrc = this.logoPreview || this.form.logo_path;
      this.cropperOpen = true;
    },

    confirmCrop() {
      const { canvas } = this.$refs.cropper.getResult();
      canvas.toBlob((blob) => {
        this.logoFile = new File([blob], 'logo.png', { type: 'image/png' });
        if (this.logoPreview) URL.revokeObjectURL(this.logoPreview);
        this.logoPreview = URL.createObjectURL(blob);
        this.cropperOpen = false;
      }, 'image/png');
    },

    cancelCrop() {
      this.cropperOpen = false;
      if (!this.logoFile && !this.form.logo_path) {
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
      payload.append('name', this.form.name);
      payload.append('slug', this.form.slug);
      if (this.logoFile) payload.append('logo', this.logoFile, 'logo.png');

      try {
        await companyService.update(this.$route.params.id, payload);
        this.$router.push({ name: 'companies.show', params: { id: this.$route.params.id } });
      } catch (err) {
        if (err.response?.status === 422) {
          this.errors = err.response.data.errors;
        } else {
          this.generalError = err.response?.data?.message || 'Error al guardar.';
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

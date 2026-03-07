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

    <div style="max-width: 760px">
      <form @submit.prevent="submit">

        <div class="card mb-4">
          <div class="card-header fw-semibold">Información general</div>
          <div class="card-body row g-3">

            <div class="col-12">
              <label class="form-label">Nombre *</label>
              <input v-model="form.name" type="text" class="form-control" :class="errorClass('name')" />
              <div class="invalid-feedback">{{ errors.name?.[0] }}</div>
            </div>

            <div class="col-md-6">
              <label class="form-label">Slug *</label>
              <div class="input-group">
                <span class="input-group-text text-muted">dominio.com/</span>
                <input v-model="form.slug" type="text" class="form-control" :class="errorClass('slug')"
                  @input="form.slug = slugify(form.slug)" />
              </div>
              <div class="invalid-feedback d-block" v-if="errors.slug">{{ errors.slug[0] }}</div>
            </div>

            <div class="col-md-6">
              <label class="form-label">Logotipo</label>
              <input type="file" class="form-control" accept="image/*" @change="onLogoChange" />
              <img v-if="logoPreview" :src="logoPreview" class="mt-2 rounded" style="height: 70px; object-fit: contain" />
              <img v-else-if="form.logo_path" :src="form.logo_path" class="mt-2 rounded" style="height: 70px; object-fit: contain" />
            </div>

            <div class="col-md-6">
              <label class="form-label">Teléfono</label>
              <input v-model="form.phone" type="text" class="form-control" />
            </div>

            <div class="col-md-6">
              <label class="form-label">Email</label>
              <input v-model="form.email" type="email" class="form-control" :class="errorClass('email')" />
              <div class="invalid-feedback">{{ errors.email?.[0] }}</div>
            </div>

            <div class="col-12">
              <label class="form-label">Dirección</label>
              <input v-model="form.address" type="text" class="form-control" />
            </div>

            <div class="col-12">
              <label class="form-label">Descripción</label>
              <textarea v-model="form.description" class="form-control" rows="3"></textarea>
            </div>
          </div>
        </div>

        <div class="card mb-4">
          <div class="card-header fw-semibold">Redes sociales</div>
          <div class="card-body row g-3">
            <div class="col-md-6" v-for="red in redesSociales" :key="red.key">
              <label class="form-label">{{ red.label }}</label>
              <input v-model="form[red.key]" type="url" class="form-control" :placeholder="red.placeholder" />
            </div>
          </div>
        </div>

        <div class="card mb-4">
          <div class="card-header fw-semibold">Diseño de tarjeta</div>
          <div class="card-body row g-3">

            <div class="col-md-6">
              <label class="form-label">Plantilla</label>
              <select v-model="form.design_settings.template" class="form-select">
                <option value="modern">Modern</option>
                <option value="classic">Classic</option>
                <option value="minimal">Minimal</option>
                <option value="bold">Bold</option>
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label">Fuente</label>
              <select v-model="form.design_settings.font_family" class="form-select">
                <option v-for="f in fonts" :key="f" :value="f">{{ f }}</option>
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label">Border Radius: {{ form.design_settings.border_radius }}px</label>
              <input v-model.number="form.design_settings.border_radius" type="range" class="form-range" min="0" max="50" />
            </div>

            <div class="col-12">
              <label class="form-label d-block mb-2">Colores</label>
              <div class="d-flex flex-wrap gap-4">
                <div v-for="c in colorFields" :key="c.key" class="d-flex flex-column align-items-center gap-1">
                  <input type="color" class="form-control form-control-color"
                    v-model="form.design_settings[c.key]" :title="c.label" />
                  <small class="text-muted">{{ c.label }}</small>
                </div>
              </div>
            </div>

            <div class="col-12 d-flex gap-4">
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" v-model="form.design_settings.show_services" id="showServices" />
                <label class="form-check-label" for="showServices">Mostrar servicios</label>
              </div>
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" v-model="form.design_settings.show_products" id="showProducts" />
                <label class="form-check-label" for="showProducts">Mostrar productos</label>
              </div>
            </div>
          </div>
        </div>

        <!-- Preview -->
        <div class="card mb-4" :style="themePreviewStyle">
          <div class="card-body text-center py-4">
            <p class="fw-bold mb-1" :style="{ color: form.design_settings.text_color }">Vista previa del tema</p>
            <p class="mb-0" :style="{ color: form.design_settings.accent_color, fontFamily: form.design_settings.font_family }">
              {{ form.name }}
            </p>
          </div>
        </div>

        <div v-if="generalError" class="alert alert-danger">{{ generalError }}</div>
        <div v-if="success" class="alert alert-success">Empresa actualizada correctamente.</div>

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
  </template>
</template>

<script>
import companyService from '@/services/companyService.js';

const defaultDesign = {
  template: 'modern', primary_color: '#3B82F6', secondary_color: '#1E40AF',
  background_color: '#FFFFFF', text_color: '#111827', accent_color: '#F59E0B',
  font_family: 'Inter', border_radius: 8, show_services: true, show_products: true,
};

export default {
  name: 'CompanyEdit',

  data() {
    return {
      loading: true,
      saving: false,
      errors: {},
      generalError: null,
      success: false,
      logoPreview: null,
      logoFile: null,
      form: { design_settings: { ...defaultDesign } },
      fonts: ['Inter', 'Roboto', 'Poppins', 'Montserrat', 'Lato', 'Playfair Display'],
      colorFields: [
        { key: 'primary_color', label: 'Principal' },
        { key: 'secondary_color', label: 'Secundario' },
        { key: 'background_color', label: 'Fondo' },
        { key: 'text_color', label: 'Texto' },
        { key: 'accent_color', label: 'Acento' },
      ],
      redesSociales: [
        { key: 'facebook',  label: 'Facebook',  placeholder: 'https://facebook.com/...' },
        { key: 'instagram', label: 'Instagram', placeholder: 'https://instagram.com/...' },
        { key: 'twitter',   label: 'Twitter/X', placeholder: 'https://twitter.com/...' },
        { key: 'youtube',   label: 'YouTube',   placeholder: 'https://youtube.com/...' },
        { key: 'website',   label: 'Sitio web', placeholder: 'https://...' },
      ],
    };
  },

  computed: {
    themePreviewStyle() {
      const s = this.form.design_settings;
      return {
        backgroundColor: s.background_color,
        borderRadius: s.border_radius + 'px',
        border: `2px solid ${s.primary_color}`,
      };
    },
  },

  async created() {
    const { data } = await companyService.get(this.$route.params.id);
    this.form = {
      ...data,
      design_settings: { ...defaultDesign, ...(data.design_settings ?? {}) },
    };
    this.loading = false;
  },

  methods: {
    slugify(v) {
      return v.toLowerCase().replace(/\s+/g, '-').replace(/[^a-z0-9\-_]/g, '');
    },

    onLogoChange(e) {
      const file = e.target.files[0];
      if (!file) return;
      this.logoFile = file;
      this.logoPreview = URL.createObjectURL(file);
    },

    errorClass(field) {
      return this.errors[field] ? 'is-invalid' : '';
    },

    async submit() {
      this.saving = true;
      this.errors = {};
      this.generalError = null;
      this.success = false;

      const payload = new FormData();

      const fields = ['name', 'slug', 'phone', 'email', 'address', 'address_notes',
                      'description', 'facebook', 'instagram', 'twitter', 'youtube', 'website'];

      fields.forEach(key => {
        if (this.form[key] != null) payload.append(key, this.form[key]);
      });

      Object.entries(this.form.design_settings).forEach(([k, v]) => {
        payload.append(`design_settings[${k}]`, v);
      });

      if (this.logoFile) payload.append('logo', this.logoFile);

      try {
        await companyService.update(this.$route.params.id, payload);
        this.success = true;
        window.scrollTo(0, 0);
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

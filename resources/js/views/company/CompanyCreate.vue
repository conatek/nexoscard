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

  <form @submit.prevent="submit">
    <div class="row g-4">

      <!-- ── Columna izquierda: datos ── -->
      <div class="col-lg-7">

        <!-- Información general -->
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
              <input type="file" class="form-control" accept="image/*" @change="onLogoChange" />
              <img v-if="logoPreview" :src="logoPreview" class="mt-2 rounded border" style="height: 72px; object-fit: contain; background:#f8f9fa; padding:4px" />
            </div>

            <div class="col-md-6">
              <label class="form-label">Teléfono</label>
              <input v-model="form.phone" type="text" class="form-control" placeholder="+57 300 000 0000" />
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
              <textarea v-model="form.description" class="form-control" rows="3"
                placeholder="Breve descripción de la empresa..."></textarea>
            </div>

          </div>
        </div>

        <!-- Redes sociales -->
        <div class="card mb-4">
          <div class="card-header fw-semibold">Redes sociales</div>
          <div class="card-body row g-3">
            <div class="col-md-6" v-for="red in redesSociales" :key="red.key">
              <label class="form-label">{{ red.label }}</label>
              <input v-model="form[red.key]" type="url" class="form-control" :placeholder="red.placeholder" />
            </div>
          </div>
        </div>

      </div>

      <!-- ── Columna derecha: diseño + preview ── -->
      <div class="col-lg-5">
        <div style="position: sticky; top: 12px;">

          <!-- Diseño -->
          <div class="card mb-4">
            <div class="card-header fw-semibold">Diseño de tarjeta</div>
            <div class="card-body row g-3">

              <div class="col-6">
                <label class="form-label">Plantilla</label>
                <select v-model="form.design_settings.template" class="form-select form-select-sm">
                  <option value="modern">Modern</option>
                  <option value="classic">Classic</option>
                  <option value="minimal">Minimal</option>
                  <option value="bold">Bold</option>
                </select>
              </div>

              <div class="col-6">
                <label class="form-label">Fuente</label>
                <select v-model="form.design_settings.font_family" class="form-select form-select-sm">
                  <option v-for="f in fonts" :key="f" :value="f">{{ f }}</option>
                </select>
              </div>

              <div class="col-12">
                <label class="form-label">Colores</label>
                <div class="d-flex flex-wrap gap-3">
                  <div v-for="c in colorFields" :key="c.key" class="d-flex flex-column align-items-center gap-1">
                    <input type="color" class="form-control form-control-color"
                      style="width: 40px; height: 34px; padding: 2px"
                      v-model="form.design_settings[c.key]" :title="c.label" />
                    <small class="text-muted" style="font-size:.68rem">{{ c.label }}</small>
                  </div>
                </div>
              </div>

              <div class="col-12">
                <label class="form-label">
                  Redondeo: <span class="fw-semibold">{{ form.design_settings.border_radius }}px</span>
                </label>
                <input v-model.number="form.design_settings.border_radius" type="range" class="form-range" min="0" max="50" />
              </div>

              <div class="col-12 d-flex gap-4">
                <div class="form-check form-switch">
                  <input class="form-check-input" type="checkbox" v-model="form.design_settings.show_services" id="showServices" />
                  <label class="form-check-label small" for="showServices">Servicios</label>
                </div>
                <div class="form-check form-switch">
                  <input class="form-check-input" type="checkbox" v-model="form.design_settings.show_products" id="showProducts" />
                  <label class="form-check-label small" for="showProducts">Productos</label>
                </div>
              </div>

            </div>
          </div>

          <!-- Preview -->
          <div class="card mb-4" :style="previewCardStyle">
            <div class="card-body p-0 overflow-hidden" :style="{ borderRadius: form.design_settings.border_radius + 'px' }">

              <!-- Mini hero -->
              <div class="px-3 py-4 text-center text-white"
                :style="{ background: `linear-gradient(135deg, ${form.design_settings.primary_color}, ${form.design_settings.secondary_color})`, fontFamily: form.design_settings.font_family }">
                <div class="rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center"
                  style="width:52px; height:52px; background:rgba(255,255,255,.2); font-size:1.3rem">
                  <span v-if="logoPreview"><img :src="logoPreview" style="width:100%;height:100%;object-fit:contain;border-radius:50%" /></span>
                  <span v-else>🏢</span>
                </div>
                <div class="fw-bold" style="font-size:.95rem">{{ form.name || 'Nombre de la empresa' }}</div>
                <div style="font-size:.75rem; opacity:.8">Cargo de ejemplo · Empleado</div>
              </div>

              <!-- Mini acciones -->
              <div class="d-flex border-bottom" :style="{ background: form.design_settings.background_color }">
                <div class="flex-fill text-center py-2" style="font-size:.7rem; font-weight:600; color:#25D366">💬 WhatsApp</div>
                <div class="flex-fill text-center py-2 border-start border-end" style="font-size:.7rem; font-weight:600"
                  :style="{ color: form.design_settings.primary_color }">📞 Llamar</div>
                <div class="flex-fill text-center py-2" style="font-size:.7rem; font-weight:600"
                  :style="{ color: form.design_settings.accent_color }">✉️ Email</div>
              </div>

              <!-- Mini descripción -->
              <div class="px-3 py-2 text-muted" style="font-size:.72rem"
                :style="{ background: form.design_settings.background_color, color: form.design_settings.text_color + 'aa', fontFamily: form.design_settings.font_family }">
                Descripción profesional del contacto aparecerá aquí.
              </div>

            </div>
          </div>

          <!-- Acciones -->
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

        </div>
      </div>

    </div>
  </form>
</template>

<script>
import companyService from '@/services/companyService.js';

export default {
  name: 'CompanyCreate',

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
        phone: '',
        email: '',
        address: '',
        description: '',
        facebook: '',
        instagram: '',
        twitter: '',
        youtube: '',
        website: '',
        design_settings: {
          template:         'modern',
          primary_color:    '#3B82F6',
          secondary_color:  '#1E40AF',
          background_color: '#FFFFFF',
          text_color:       '#111827',
          accent_color:     '#F59E0B',
          font_family:      'Inter',
          border_radius:    8,
          show_services:    true,
          show_products:    true,
        },
      },

      fonts: ['Inter', 'Roboto', 'Poppins', 'Montserrat', 'Lato', 'Playfair Display'],

      colorFields: [
        { key: 'primary_color',    label: 'Principal' },
        { key: 'secondary_color',  label: 'Secundario' },
        { key: 'background_color', label: 'Fondo' },
        { key: 'text_color',       label: 'Texto' },
        { key: 'accent_color',     label: 'Acento' },
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
    previewCardStyle() {
      const s = this.form.design_settings;
      return {
        borderRadius: s.border_radius + 'px',
        border: `2px solid ${s.primary_color}`,
        overflow: 'hidden',
      };
    },
  },

  methods: {
    slugify(v) {
      return v.toLowerCase().replace(/\s+/g, '-').replace(/[^a-z0-9\-_]/g, '');
    },

    // Genera el slug automáticamente desde el nombre mientras no se edite a mano
    autoSlug() {
      if (!this.slugManuallyEdited) {
        this.form.slug = this.slugify(this.form.name);
      }
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
      this.loading = true;
      this.errors = {};
      this.generalError = null;

      const payload = new FormData();

      Object.entries(this.form).forEach(([key, value]) => {
        if (key === 'design_settings') {
          Object.entries(value).forEach(([dKey, dVal]) => {
            payload.append(`design_settings[${dKey}]`, dVal);
          });
        } else if (value !== null && value !== '') {
          payload.append(key, value);
        }
      });

      if (this.logoFile) payload.append('logo', this.logoFile);

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

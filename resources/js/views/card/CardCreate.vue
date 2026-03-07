<template>
  <div class="app-page-title">
    <div class="page-title-wrapper">
      <div class="page-title-heading">
        <div class="page-title-icon"><i class="fa fa-id-card icon-gradient bg-mean-fruit"></i></div>
        <div>Nueva tarjeta<div class="page-title-subheading">{{ companyName }}</div></div>
      </div>
      <div class="page-title-actions">
        <router-link :to="{ name: 'companies.show', params: { id: $route.params.companyId } }"
                     class="btn btn-outline-secondary btn-sm">
          <i class="fa fa-arrow-left me-1"></i> Volver
        </router-link>
      </div>
    </div>
  </div>

  <form @submit.prevent="submit" style="max-width: 680px">
    <div class="card mb-4">
      <div class="card-header fw-semibold">Datos personales</div>
      <div class="card-body row g-3">

        <div class="col-md-6">
          <label class="form-label">Nombre *</label>
          <input v-model="form.first_name" type="text" class="form-control" :class="errorClass('first_name')" />
          <div class="invalid-feedback">{{ errors.first_name?.[0] }}</div>
        </div>

        <div class="col-md-6">
          <label class="form-label">Apellido *</label>
          <input v-model="form.last_name" type="text" class="form-control" :class="errorClass('last_name')" />
          <div class="invalid-feedback">{{ errors.last_name?.[0] }}</div>
        </div>

        <div class="col-md-6">
          <label class="form-label">Slug *</label>
          <div class="input-group">
            <span class="input-group-text text-muted small">/empresa/</span>
            <input v-model="form.slug" type="text" class="form-control" :class="errorClass('slug')"
              @input="form.slug = slugify(form.slug)" />
          </div>
          <div class="invalid-feedback d-block" v-if="errors.slug">{{ errors.slug[0] }}</div>
          <div class="form-text">URL única de esta tarjeta dentro de la empresa.</div>
        </div>

        <div class="col-md-6">
          <label class="form-label">Cargo</label>
          <input v-model="form.job_title" type="text" class="form-control" placeholder="Ej: Gerente Comercial" />
        </div>

        <div class="col-12">
          <label class="form-label">Foto</label>
          <input type="file" class="form-control" accept="image/*" @change="onPhotoChange" />
          <img v-if="photoPreview" :src="photoPreview" class="mt-2 rounded-circle"
               style="width: 80px; height: 80px; object-fit: cover" />
        </div>

        <div class="col-12">
          <label class="form-label">Perfil profesional</label>
          <textarea v-model="form.description" class="form-control" rows="3"
                    placeholder="Breve descripción profesional..."></textarea>
        </div>
      </div>
    </div>

    <div class="card mb-4">
      <div class="card-header fw-semibold">Contacto</div>
      <div class="card-body row g-3">

        <div class="col-md-6">
          <label class="form-label">Teléfono móvil</label>
          <input v-model="form.mobile_phone" type="text" class="form-control" />
        </div>

        <div class="col-md-6">
          <label class="form-label">WhatsApp</label>
          <input v-model="form.whatsapp" type="text" class="form-control" />
        </div>

        <div class="col-md-6">
          <label class="form-label">Email</label>
          <input v-model="form.email" type="email" class="form-control" :class="errorClass('email')" />
          <div class="invalid-feedback">{{ errors.email?.[0] }}</div>
        </div>

        <div class="col-md-6 d-flex align-items-end">
          <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" v-model="form.is_active" id="isActive" />
            <label class="form-check-label" for="isActive">Tarjeta activa</label>
          </div>
        </div>
      </div>
    </div>

    <div v-if="generalError" class="alert alert-danger">{{ generalError }}</div>

    <div class="d-flex justify-content-end gap-2 mb-4">
      <router-link :to="{ name: 'companies.show', params: { id: $route.params.companyId } }"
                   class="btn btn-outline-secondary">Cancelar</router-link>
      <button type="submit" class="btn btn-primary" :disabled="saving">
        <span v-if="saving" class="spinner-border spinner-border-sm me-1"></span>
        Crear tarjeta
      </button>
    </div>
  </form>
</template>

<script>
import cardService    from '@/services/cardService.js';
import companyService from '@/services/companyService.js';

export default {
  name: 'CardCreate',

  data() {
    return {
      companyName: '',
      saving: false,
      errors: {},
      generalError: null,
      photoPreview: null,
      photoFile: null,
      form: {
        first_name: '', last_name: '', slug: '', job_title: '',
        mobile_phone: '', whatsapp: '', email: '', description: '', is_active: true,
      },
    };
  },

  async created() {
    const { data } = await companyService.get(this.$route.params.companyId);
    this.companyName = data.name;
  },

  methods: {
    slugify(v) {
      return v.toLowerCase().replace(/\s+/g, '-').replace(/[^a-z0-9\-_]/g, '');
    },

    onPhotoChange(e) {
      const file = e.target.files[0];
      if (!file) return;
      this.photoFile = file;
      this.photoPreview = URL.createObjectURL(file);
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
      if (this.photoFile) payload.append('photo', this.photoFile);

      try {
        await cardService.store(this.$route.params.companyId, payload);
        this.$router.push({ name: 'companies.show', params: { id: this.$route.params.companyId } });
      } catch (err) {
        if (err.response?.status === 422) {
          this.errors = err.response.data.errors;
        } else {
          this.generalError = err.response?.data?.message || 'Error al crear la tarjeta.';
        }
      } finally {
        this.saving = false;
      }
    },
  },
};
</script>

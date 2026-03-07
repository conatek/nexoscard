<template>
  <div v-if="loading" class="text-center py-5">
    <div class="spinner-border text-primary"></div>
  </div>

  <template v-else>
    <div class="app-page-title">
      <div class="page-title-wrapper">
        <div class="page-title-heading">
          <div class="page-title-icon"><i class="fa fa-edit icon-gradient bg-mean-fruit"></i></div>
          <div>Editar tarjeta
            <div class="page-title-subheading">{{ form.first_name }} {{ form.last_name }}</div>
          </div>
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
          </div>

          <div class="col-md-6">
            <label class="form-label">Cargo</label>
            <input v-model="form.job_title" type="text" class="form-control" />
          </div>

          <div class="col-12">
            <label class="form-label">Foto</label>
            <input type="file" class="form-control" accept="image/*" @change="onPhotoChange" />
            <img v-if="photoPreview" :src="photoPreview" class="mt-2 rounded-circle"
                 style="width: 80px; height: 80px; object-fit: cover" />
            <img v-else-if="form.photo_path" :src="form.photo_path" class="mt-2 rounded-circle"
                 style="width: 80px; height: 80px; object-fit: cover" />
          </div>

          <div class="col-12">
            <label class="form-label">Perfil profesional</label>
            <textarea v-model="form.description" class="form-control" rows="3"></textarea>
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
      <div v-if="success" class="alert alert-success">Tarjeta actualizada correctamente.</div>

      <div class="d-flex justify-content-end gap-2 mb-4">
        <router-link :to="{ name: 'companies.show', params: { id: $route.params.companyId } }"
                     class="btn btn-outline-secondary">Cancelar</router-link>
        <button type="submit" class="btn btn-primary" :disabled="saving">
          <span v-if="saving" class="spinner-border spinner-border-sm me-1"></span>
          Guardar cambios
        </button>
      </div>
    </form>
  </template>
</template>

<script>
import cardService from '@/services/cardService.js';

export default {
  name: 'CardEdit',

  data() {
    return {
      loading: true,
      saving: false,
      errors: {},
      generalError: null,
      success: false,
      photoPreview: null,
      photoFile: null,
      form: {},
    };
  },

  async created() {
    const { data } = await cardService.get(
      this.$route.params.companyId,
      this.$route.params.cardId
    );
    this.form = data;
    this.loading = false;
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
      this.success = false;

      const payload = new FormData();
      const fields = ['first_name', 'last_name', 'slug', 'job_title',
                      'mobile_phone', 'whatsapp', 'email', 'description', 'is_active'];
      fields.forEach(k => {
        if (this.form[k] != null) payload.append(k, this.form[k]);
      });
      if (this.photoFile) payload.append('photo', this.photoFile);

      try {
        await cardService.update(
          this.$route.params.companyId,
          this.$route.params.cardId,
          payload
        );
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

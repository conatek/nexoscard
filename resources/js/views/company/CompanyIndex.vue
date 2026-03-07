<template>
  <div class="app-page-title">
    <div class="page-title-wrapper">
      <div class="page-title-heading">
        <div class="page-title-icon"><i class="fa fa-building icon-gradient bg-mean-fruit"></i></div>
        <div>Mis Empresas</div>
      </div>
      <div class="page-title-actions">
        <router-link :to="{ name: 'companies.create' }" class="btn btn-primary">
          <i class="fa fa-plus me-1"></i> Nueva Empresa
        </router-link>
      </div>
    </div>
  </div>

  <div class="row">
    <!-- Estado de carga -->
    <div v-if="loading" class="col-12 text-center py-5">
      <div class="spinner-border text-primary"></div>
    </div>

    <!-- Sin empresas -->
    <div v-else-if="companies.length === 0" class="col-12">
      <div class="card text-center py-5">
        <div class="card-body">
          <i class="fa fa-building fa-3x text-muted mb-3"></i>
          <h5 class="text-muted">Aún no tienes empresas</h5>
          <router-link :to="{ name: 'companies.create' }" class="btn btn-primary mt-2">
            Crear mi primera empresa
          </router-link>
        </div>
      </div>
    </div>

    <!-- Lista de empresas -->
    <div v-else class="col-md-4 mb-4" v-for="company in companies" :key="company.id">
      <div class="card h-100">
        <!-- Logo o placeholder -->
        <div class="card-img-top d-flex align-items-center justify-content-center bg-light"
             style="height: 140px; overflow: hidden;">
          <img v-if="company.logo_path" :src="company.logo_path"
               class="img-fluid" style="max-height: 140px; object-fit: contain;" />
          <i v-else class="fa fa-building fa-3x text-muted"></i>
        </div>

        <div class="card-body">
          <h5 class="card-title mb-1">{{ company.name }}</h5>
          <p class="text-muted small mb-2">
            <i class="fa fa-link me-1"></i>/{{ company.slug }}
          </p>
          <p class="card-text text-muted small text-truncate">{{ company.description }}</p>

          <div class="d-flex gap-3 text-center mt-3">
            <div class="flex-fill">
              <div class="fw-bold">{{ company.cards_count ?? 0 }}</div>
              <small class="text-muted">Tarjetas</small>
            </div>
            <div class="flex-fill">
              <div class="fw-bold">{{ company.services_count ?? 0 }}</div>
              <small class="text-muted">Servicios</small>
            </div>
            <div class="flex-fill">
              <div class="fw-bold">{{ company.products_count ?? 0 }}</div>
              <small class="text-muted">Productos</small>
            </div>
          </div>
        </div>

        <div class="card-footer d-flex gap-2">
          <router-link :to="{ name: 'companies.show', params: { id: company.id } }"
                       class="btn btn-sm btn-primary flex-fill">
            <i class="fa fa-eye me-1"></i> Gestionar
          </router-link>
          <router-link :to="{ name: 'companies.edit', params: { id: company.id } }"
                       class="btn btn-sm btn-outline-secondary">
            <i class="fa fa-edit"></i>
          </router-link>
          <button @click="confirmDelete(company)" class="btn btn-sm btn-outline-danger">
            <i class="fa fa-trash"></i>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal confirmación de borrado -->
  <div v-if="toDelete" class="modal d-block" tabindex="-1" style="background: rgba(0,0,0,.5)">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Eliminar empresa</h5>
          <button type="button" class="btn-close" @click="toDelete = null"></button>
        </div>
        <div class="modal-body">
          ¿Eliminar <strong>{{ toDelete.name }}</strong>? Se eliminarán también todas sus tarjetas, servicios y productos.
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" @click="toDelete = null">Cancelar</button>
          <button class="btn btn-danger" @click="deleteCompany" :disabled="deleting">
            <span v-if="deleting" class="spinner-border spinner-border-sm me-1"></span>
            Eliminar
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import companyService from '@/services/companyService.js';

export default {
  name: 'CompanyIndex',

  data() {
    return {
      companies: [],
      loading: true,
      toDelete: null,
      deleting: false,
    };
  },

  async created() {
    await this.load();
  },

  methods: {
    async load() {
      this.loading = true;
      try {
        const { data } = await companyService.all();
        this.companies = data;
      } finally {
        this.loading = false;
      }
    },

    confirmDelete(company) {
      this.toDelete = company;
    },

    async deleteCompany() {
      this.deleting = true;
      try {
        await companyService.destroy(this.toDelete.id);
        this.companies = this.companies.filter(c => c.id !== this.toDelete.id);
        this.toDelete = null;
      } finally {
        this.deleting = false;
      }
    },
  },
};
</script>

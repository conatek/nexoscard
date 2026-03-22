<template>
  <div v-if="loading" class="text-center py-5">
    <div class="spinner-border text-primary"></div>
  </div>

  <template v-else>
    <!-- Cabecera de empresa -->
    <div class="app-page-title">
      <div class="page-title-wrapper">
        <div class="page-title-heading">
          <div class="page-title-icon"
               :style="company.logo_path
                 ? 'width:auto;min-width:60px;height:60px;padding:6px'
                 : ''">
            <img v-if="company.logo_path"
                 :src="company.logo_path"
                 style="height:48px;width:auto;max-width:200px;display:block;border-radius:4px;object-fit:contain" />
            <i v-else class="fa fa-building icon-gradient bg-mean-fruit"></i>
          </div>
          <div>
            {{ company.name }}
            <div class="page-title-subheading text-muted">
              <i class="fa fa-link me-1"></i>/{{ company.slug }}
            </div>
          </div>
        </div>
        <div class="page-title-actions d-flex gap-2">
          <router-link :to="{ name: 'settings.editor', params: { companyId: company.id } }"
                       class="btn btn-outline-primary btn-sm">
            <i class="fa fa-palette me-1"></i> Personalizar Plantilla
          </router-link>
          <router-link :to="{ name: 'companies.edit', params: { id: company.id } }"
                       class="btn btn-outline-secondary btn-sm">
            <i class="fa fa-edit me-1"></i> Editar empresa
          </router-link>
          <router-link :to="{ name: 'cards.create', params: { companyId: company.id } }"
                       class="btn btn-primary btn-sm">
            <i class="fa fa-plus me-1"></i> Nueva tarjeta
          </router-link>
        </div>
      </div>
    </div>

    <!-- Resumen -->
    <div class="row mb-4">
      <div class="col-md-4 mb-3">
        <div class="card text-center py-3">
          <div class="card-body">
            <div class="display-6 fw-bold text-primary">{{ company.cards?.length ?? 0 }}</div>
            <div class="text-muted">Tarjetas</div>
          </div>
        </div>
      </div>
      <div class="col-md-4 mb-3">
        <div class="card text-center py-3">
          <div class="card-body">
            <div class="display-6 fw-bold text-success">{{ company.services?.length ?? 0 }}</div>
            <div class="text-muted">Servicios</div>
          </div>
        </div>
      </div>
      <div class="col-md-4 mb-3">
        <div class="card text-center py-3">
          <div class="card-body">
            <div class="display-6 fw-bold text-warning">{{ company.products?.length ?? 0 }}</div>
            <div class="text-muted">Productos</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Tarjetas de empleados -->
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-semibold">Tarjetas de presentación</span>
        <router-link :to="{ name: 'cards.create', params: { companyId: company.id } }"
                     class="btn btn-sm btn-primary">
          <i class="fa fa-plus me-1"></i> Nueva
        </router-link>
      </div>
      <div class="card-body">
        <div v-if="!company.cards?.length" class="text-center text-muted py-4">
          <i class="fa fa-id-card fa-2x mb-2"></i>
          <p class="mb-0">Sin tarjetas. Crea la primera.</p>
        </div>

        <div class="table-responsive" v-else>
          <table class="table table-hover align-middle mb-0">
            <thead>
              <tr>
                <th>Persona</th>
                <th>Cargo</th>
                <th>URL</th>
                <th>Estado</th>
                <th class="text-end">Acciones</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="card in company.cards" :key="card.id">
                <td>
                  <div class="d-flex align-items-center gap-2">
                    <img v-if="card.photo_path" :src="card.photo_path"
                         class="rounded-circle" width="36" height="36" style="object-fit:cover" />
                    <div v-else class="rounded-circle bg-secondary d-flex align-items-center justify-content-center"
                         style="width:36px;height:36px">
                      <i class="fa fa-user text-white small"></i>
                    </div>
                    <div>
                      <div class="fw-medium">{{ card.first_name }} {{ card.last_name }}</div>
                      <small class="text-muted">{{ card.email }}</small>
                    </div>
                  </div>
                </td>
                <td>{{ card.job_title || '—' }}</td>
                <td>
                  <small class="text-muted">/{{ company.slug }}/{{ card.slug }}</small>
                </td>
                <td>
                  <span class="badge" :class="card.is_active ? 'bg-success' : 'bg-secondary'">
                    {{ card.is_active ? 'Activa' : 'Inactiva' }}
                  </span>
                </td>
                <td class="text-end">
                  <router-link :to="{ name: 'cards.edit', params: { companyId: company.id, cardId: card.id } }"
                               class="btn btn-sm btn-outline-secondary me-1">
                    <i class="fa fa-edit"></i>
                  </router-link>
                  <button @click="confirmDeleteCard(card)" class="btn btn-sm btn-outline-danger">
                    <i class="fa fa-trash"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </template>

  <!-- Modal eliminar tarjeta -->
  <div v-if="cardToDelete" class="modal d-block" tabindex="-1" style="background: rgba(0,0,0,.5)">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Eliminar tarjeta</h5>
          <button type="button" class="btn-close" @click="cardToDelete = null"></button>
        </div>
        <div class="modal-body">
          ¿Eliminar la tarjeta de <strong>{{ cardToDelete.first_name }} {{ cardToDelete.last_name }}</strong>?
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" @click="cardToDelete = null">Cancelar</button>
          <button class="btn btn-danger" @click="deleteCard" :disabled="deleting">
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
import cardService    from '@/services/cardService.js';

export default {
  name: 'CompanyShow',

  data() {
    return {
      company: {},
      loading: true,
      cardToDelete: null,
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
        const { data } = await companyService.get(this.$route.params.id);
        this.company = data;
      } finally {
        this.loading = false;
      }
    },

    confirmDeleteCard(card) {
      this.cardToDelete = card;
    },

    async deleteCard() {
      this.deleting = true;
      try {
        await cardService.destroy(this.company.id, this.cardToDelete.id);
        this.company.cards = this.company.cards.filter(c => c.id !== this.cardToDelete.id);
        this.cardToDelete = null;
      } finally {
        this.deleting = false;
      }
    },
  },
};
</script>

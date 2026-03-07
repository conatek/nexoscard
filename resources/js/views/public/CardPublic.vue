<template>
  <!-- Pantalla de carga -->
  <div v-if="loading" class="card-loading">
    <div class="spinner"></div>
  </div>

  <!-- Error 404 -->
  <div v-else-if="notFound" class="card-not-found">
    <div class="nf-icon">🪪</div>
    <h2>Tarjeta no encontrada</h2>
    <p>Esta tarjeta no existe o no está disponible.</p>
  </div>

  <!-- Tarjeta -->
  <div v-else class="bc-root" :style="cssVars">

    <!-- Hero -->
    <div class="bc-hero" :style="{ background: `linear-gradient(160deg, var(--color-primary) 0%, var(--color-secondary) 100%)` }">
      <div class="bc-hero__logo" v-if="company.logo_path">
        <img :src="company.logo_path" :alt="company.name" />
      </div>

      <div class="bc-hero__photo">
        <img v-if="card.photo_path" :src="card.photo_path" :alt="card.first_name" />
        <div v-else class="bc-hero__photo--placeholder">
          {{ initials }}
        </div>
      </div>

      <div class="bc-hero__info">
        <h1>{{ card.first_name }} {{ card.last_name }}</h1>
        <p class="bc-hero__job" v-if="card.job_title">{{ card.job_title }}</p>
        <p class="bc-hero__company">{{ company.name }}</p>
      </div>
    </div>

    <!-- Acciones rápidas de contacto -->
    <div class="bc-actions">
      <a v-if="card.whatsapp" :href="whatsappUrl" target="_blank" class="bc-action bc-action--whatsapp">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
        WhatsApp
      </a>
      <a v-if="card.mobile_phone" :href="`tel:${card.mobile_phone}`" class="bc-action bc-action--phone">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>
        Llamar
      </a>
      <a v-if="card.email" :href="`mailto:${card.email}`" class="bc-action bc-action--email">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
        Email
      </a>
    </div>

    <!-- Descripción profesional -->
    <div class="bc-section" v-if="card.description">
      <p class="bc-description">{{ card.description }}</p>
    </div>

    <!-- Datos de contacto detallados -->
    <div class="bc-section">
      <h2 class="bc-section__title">Contacto</h2>
      <div class="bc-contacts">
        <a v-if="card.mobile_phone" :href="`tel:${card.mobile_phone}`" class="bc-contact-item">
          <span class="bc-contact-item__icon" :style="{ background: 'var(--color-primary)' }">📱</span>
          <span>{{ card.mobile_phone }}</span>
        </a>
        <a v-if="card.whatsapp" :href="whatsappUrl" target="_blank" class="bc-contact-item">
          <span class="bc-contact-item__icon" :style="{ background: '#25D366' }">💬</span>
          <span>{{ card.whatsapp }}</span>
        </a>
        <a v-if="card.email" :href="`mailto:${card.email}`" class="bc-contact-item">
          <span class="bc-contact-item__icon" :style="{ background: 'var(--color-accent)' }">✉️</span>
          <span>{{ card.email }}</span>
        </a>
      </div>
    </div>

    <!-- Servicios de la empresa -->
    <div class="bc-section" v-if="design.show_services && company.services?.length">
      <h2 class="bc-section__title">Servicios</h2>
      <div class="bc-grid">
        <div class="bc-grid__item" v-for="service in company.services" :key="service.id">
          <div class="bc-grid__img" v-if="service.image_path">
            <img :src="service.image_path" :alt="service.name" />
          </div>
          <div class="bc-grid__img bc-grid__img--placeholder" v-else :style="{ background: 'var(--color-primary)' }">
            🛠️
          </div>
          <div class="bc-grid__body">
            <h3>{{ service.name }}</h3>
            <p v-if="service.description">{{ service.description }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Productos de la empresa -->
    <div class="bc-section" v-if="design.show_products && company.products?.length">
      <h2 class="bc-section__title">Productos</h2>
      <div class="bc-grid">
        <div class="bc-grid__item" v-for="product in company.products" :key="product.id">
          <div class="bc-grid__img" v-if="product.image_path">
            <img :src="product.image_path" :alt="product.name" />
          </div>
          <div class="bc-grid__img bc-grid__img--placeholder" v-else :style="{ background: 'var(--color-secondary)' }">
            📦
          </div>
          <div class="bc-grid__body">
            <h3>{{ product.name }}</h3>
            <div class="bc-price">
              <span class="bc-price__final" :style="{ color: 'var(--color-primary)' }">
                ${{ finalPrice(product) }}
              </span>
              <span v-if="product.discount > 0" class="bc-price__original">
                ${{ Number(product.price).toFixed(2) }}
              </span>
              <span v-if="product.discount > 0" class="bc-price__badge" :style="{ background: 'var(--color-accent)' }">
                -{{ product.discount }}%
              </span>
            </div>
            <p v-if="product.comment" class="bc-price__comment">{{ product.comment }}</p>
            <p v-if="product.description" class="bc-grid__desc">{{ product.description }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Redes sociales de la empresa -->
    <div class="bc-section bc-social" v-if="hasSocial">
      <h2 class="bc-section__title">Síguenos</h2>
      <div class="bc-social__links">
        <a v-if="company.facebook"  :href="company.facebook"  target="_blank" class="bc-social__link bc-social__link--fb">Facebook</a>
        <a v-if="company.instagram" :href="company.instagram" target="_blank" class="bc-social__link bc-social__link--ig">Instagram</a>
        <a v-if="company.twitter"   :href="company.twitter"   target="_blank" class="bc-social__link bc-social__link--tw">Twitter</a>
        <a v-if="company.youtube"   :href="company.youtube"   target="_blank" class="bc-social__link bc-social__link--yt">YouTube</a>
        <a v-if="company.website"   :href="company.website"   target="_blank" class="bc-social__link bc-social__link--web">Sitio web</a>
      </div>
    </div>

    <!-- Footer -->
    <footer class="bc-footer">
      <p>Tarjeta digital · <a :href="`/${company.slug}`" :style="{ color: 'var(--color-primary)' }">{{ company.name }}</a></p>
    </footer>

  </div>
</template>

<script>
import publicCardService from '@/services/publicCardService.js';

const defaultDesign = {
  primary_color: '#3B82F6', secondary_color: '#1E40AF',
  background_color: '#F9FAFB', text_color: '#111827', accent_color: '#F59E0B',
  font_family: 'Inter', border_radius: 8,
  show_services: true, show_products: true,
};

export default {
  name: 'CardPublic',

  data() {
    return {
      loading: true,
      notFound: false,
      card: {},
      company: {},
    };
  },

  computed: {
    design() {
      return { ...defaultDesign, ...(this.company.design_settings ?? {}) };
    },

    cssVars() {
      const d = this.design;
      return {
        '--color-primary':    d.primary_color,
        '--color-secondary':  d.secondary_color,
        '--color-background': d.background_color,
        '--color-text':       d.text_color,
        '--color-accent':     d.accent_color,
        '--font-family':      d.font_family,
        '--border-radius':    d.border_radius + 'px',
      };
    },

    initials() {
      return `${this.card.first_name?.[0] ?? ''}${this.card.last_name?.[0] ?? ''}`.toUpperCase();
    },

    whatsappUrl() {
      const num = this.card.whatsapp?.replace(/\D/g, '');
      return `https://wa.me/${num}`;
    },

    hasSocial() {
      return this.company.facebook || this.company.instagram ||
             this.company.twitter  || this.company.youtube  || this.company.website;
    },
  },

  async created() {
    const { companySlug, cardSlug } = this.$route.params;
    try {
      const { data } = await publicCardService.card(companySlug, cardSlug);
      this.card    = data.card;
      this.company = data.company;
    } catch (err) {
      this.notFound = true;
    } finally {
      this.loading = false;
    }
  },

  methods: {
    finalPrice(product) {
      return (product.price * (1 - product.discount / 100)).toFixed(2);
    },
  },
};
</script>

<style scoped>
/* ---- Reset y base ---- */
.bc-root {
  background-color: var(--color-background);
  color: var(--color-text);
  font-family: var(--font-family), system-ui, sans-serif;
  min-height: 100vh;
  max-width: 480px;
  margin: 0 auto;
}

/* ---- Hero ---- */
.bc-hero {
  padding: 32px 24px 24px;
  text-align: center;
  color: #fff;
}

.bc-hero__logo {
  margin-bottom: 12px;
}
.bc-hero__logo img {
  height: 48px;
  object-fit: contain;
  filter: brightness(0) invert(1);
  opacity: .85;
}

.bc-hero__photo {
  margin: 0 auto 16px;
  width: 110px;
  height: 110px;
  border-radius: 50%;
  border: 3px solid rgba(255,255,255,.4);
  overflow: hidden;
  background: rgba(255,255,255,.15);
}
.bc-hero__photo img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
.bc-hero__photo--placeholder {
  display: flex;
  align-items: center;
  justify-content: center;
  height: 100%;
  font-size: 2.2rem;
  font-weight: 700;
  color: #fff;
}

.bc-hero h1 {
  font-size: 1.5rem;
  font-weight: 700;
  margin: 0 0 4px;
}
.bc-hero__job {
  font-size: .95rem;
  opacity: .9;
  margin: 0 0 4px;
}
.bc-hero__company {
  font-size: .85rem;
  opacity: .7;
  margin: 0;
}

/* ---- Acciones rápidas ---- */
.bc-actions {
  display: flex;
  justify-content: center;
  gap: 12px;
  padding: 20px 24px;
  background: #fff;
  border-bottom: 1px solid #f0f0f0;
}
.bc-action {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 6px;
  padding: 12px 18px;
  border-radius: var(--border-radius);
  text-decoration: none;
  font-size: .75rem;
  font-weight: 600;
  flex: 1;
  transition: opacity .15s;
  color: #fff;
}
.bc-action:hover { opacity: .85; }
.bc-action--whatsapp { background: #25D366; }
.bc-action--phone    { background: var(--color-primary); }
.bc-action--email    { background: var(--color-accent); color: #111; }

/* ---- Secciones ---- */
.bc-section {
  padding: 20px 20px 0;
}
.bc-section__title {
  font-size: .7rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: .1em;
  color: var(--color-primary);
  margin: 0 0 14px;
}
.bc-description {
  font-size: .9rem;
  line-height: 1.6;
  color: var(--color-text);
  opacity: .8;
  margin: 0;
}

/* ---- Contactos ---- */
.bc-contacts { display: flex; flex-direction: column; gap: 10px; }
.bc-contact-item {
  display: flex;
  align-items: center;
  gap: 12px;
  text-decoration: none;
  color: var(--color-text);
  font-size: .9rem;
  background: #fff;
  border-radius: var(--border-radius);
  padding: 10px 14px;
  box-shadow: 0 1px 3px rgba(0,0,0,.06);
}
.bc-contact-item__icon {
  width: 34px;
  height: 34px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1rem;
  flex-shrink: 0;
}

/* ---- Grid de servicios / productos ---- */
.bc-grid { display: flex; flex-direction: column; gap: 12px; }
.bc-grid__item {
  display: flex;
  gap: 14px;
  background: #fff;
  border-radius: var(--border-radius);
  overflow: hidden;
  box-shadow: 0 1px 3px rgba(0,0,0,.06);
}
.bc-grid__img {
  width: 80px;
  min-width: 80px;
  height: 80px;
  object-fit: cover;
  overflow: hidden;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.8rem;
}
.bc-grid__img img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
.bc-grid__img--placeholder { border-radius: 0; }
.bc-grid__body {
  padding: 10px 14px 10px 0;
  flex: 1;
}
.bc-grid__body h3 {
  font-size: .9rem;
  font-weight: 600;
  margin: 0 0 4px;
}
.bc-grid__desc {
  font-size: .8rem;
  color: #666;
  margin: 4px 0 0;
}

/* ---- Precios ---- */
.bc-price {
  display: flex;
  align-items: center;
  gap: 8px;
  margin: 4px 0 2px;
}
.bc-price__final  { font-weight: 700; font-size: 1rem; }
.bc-price__original { text-decoration: line-through; font-size: .8rem; color: #999; }
.bc-price__badge {
  font-size: .7rem;
  font-weight: 700;
  color: #111;
  padding: 2px 6px;
  border-radius: 99px;
}
.bc-price__comment { font-size: .78rem; color: #555; font-style: italic; margin: 2px 0 0; }

/* ---- Redes sociales ---- */
.bc-social__links { display: flex; flex-wrap: wrap; gap: 8px; }
.bc-social__link {
  padding: 8px 16px;
  border-radius: 99px;
  font-size: .8rem;
  font-weight: 600;
  text-decoration: none;
  color: #fff;
}
.bc-social__link--fb  { background: #1877F2; }
.bc-social__link--ig  { background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888); }
.bc-social__link--tw  { background: #1DA1F2; }
.bc-social__link--yt  { background: #FF0000; }
.bc-social__link--web { background: var(--color-primary); }

/* ---- Footer ---- */
.bc-footer {
  text-align: center;
  padding: 28px 20px 40px;
  font-size: .78rem;
  color: #999;
}
.bc-footer a { text-decoration: none; font-weight: 600; }

/* ---- Loading / Not found ---- */
.card-loading, .card-not-found {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  min-height: 100vh;
  gap: 12px;
  text-align: center;
  font-family: system-ui, sans-serif;
  color: #555;
  padding: 24px;
}
.spinner {
  width: 40px;
  height: 40px;
  border: 3px solid #e5e7eb;
  border-top-color: #3B82F6;
  border-radius: 50%;
  animation: spin .7s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }
.nf-icon { font-size: 3rem; }
.card-not-found h2 { margin: 0; font-size: 1.3rem; }
.card-not-found p  { margin: 0; font-size: .9rem; }
</style>

<template>
    <div>
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="fa fa-crown icon-gradient bg-mean-fruit"></i>
                    </div>
                    <div>
                        Mi Plan
                        <div class="page-title-subheading text-muted">
                            Activa tu presencia digital profesional
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loading -->
        <div v-if="loading" class="loading-state">
            <div class="spinner-border text-primary"></div>
            <p class="text-muted mt-3">Cargando...</p>
        </div>

        <div v-else-if="plan" class="offer-wrap">
            <!-- Cinta de oferta -->
            <div v-if="plan.is_offer_active" class="offer-ribbon">
                <i class="fa fa-bolt"></i>
                Oferta especial &middot; {{ plan.discount_percent }}% de descuento
            </div>

            <div class="plan-card" :class="{ 'current': isCurrentPlan }">
                <div v-if="isCurrentPlan" class="current-badge">Plan actual</div>

                <div class="plan-header">
                    <h3 class="plan-name">{{ plan.display_name }}</h3>
                    <p class="plan-tagline">
                        Ideal para emprendedores y negocios que desean tener
                        presencia digital profesional.
                    </p>

                    <!-- Precio -->
                    <div class="price-block">
                        <div v-if="plan.is_offer_active" class="price-before">
                            <span class="before-label">Antes</span>
                            <span class="before-amount">{{ money(plan.price_regular) }}</span>
                        </div>

                        <div class="price-now">
                            <span v-if="plan.is_offer_active" class="now-label">Ahora</span>
                            <span class="now-amount">{{ money(plan.effective_price) }}</span>
                            <span class="now-period">/ año</span>
                        </div>

                        <div v-if="plan.is_offer_active" class="price-save">
                            Ahorras {{ money(plan.price_regular - plan.effective_price) }}
                        </div>
                    </div>

                    <!-- Cuenta regresiva: solo si la oferta tiene fecha de fin -->
                    <div v-if="countdown" class="countdown">
                        <i class="fa fa-clock"></i>
                        <span>Por tiempo limitado &middot; termina en</span>
                        <strong>{{ countdown }}</strong>
                    </div>
                </div>

                <!-- Bullets del plan -->
                <div class="plan-body">
                    <p class="features-title">Incluye por todo 1 año:</p>
                    <ul class="plan-features">
                        <li v-for="(feature, i) in features" :key="i">
                            <i class="fa fa-check"></i>
                            <span>{{ feature }}</span>
                        </li>
                    </ul>
                </div>

                <!-- Acción -->
                <div class="plan-action">
                    <button v-if="isCurrentPlan && isPaidActive" class="btn-plan btn-current" disabled>
                        <i class="fa fa-check-circle me-1"></i> Tu plan está activo
                    </button>
                    <router-link
                        v-else
                        :to="{ name: 'subscription.checkout', params: { planId: plan.id } }"
                        class="btn-plan btn-primary-cta"
                    >
                        <i class="fa fa-lock me-1"></i>
                        Activar por {{ money(plan.effective_price) }}
                    </router-link>
                    <p class="action-note">
                        Pago seguro con MercadoPago &middot; Suscripción por 1 año
                    </p>
                </div>
            </div>

            <!-- Nota de equipos -->
            <div class="team-note">
                <div class="team-icon"><i class="fa fa-users"></i></div>
                <div class="team-copy">
                    <strong>¿Necesitas varias tarjetas?</strong>
                    <p>
                        Este plan incluye una tarjeta digital. Si tu equipo necesita
                        varias, tenemos precio especial por volumen.
                    </p>

                    <!-- WhatsApp abre app o web segun el dispositivo; el mailto solo
                         funcionaba con un cliente de correo configurado. -->
                    <a v-if="whatsappLink" :href="whatsappLink" target="_blank" rel="noopener" class="team-btn">
                        <i class="fab fa-whatsapp"></i>
                        Cotizar por WhatsApp
                    </a>

                    <!-- Sin numero configurado, al menos el correo se puede copiar -->
                    <div v-else class="team-fallback">
                        <span>{{ supportEmail }}</span>
                        <button class="btn-copy-mail" :class="{ copied: copiedEmail }" @click="copyEmail">
                            <i class="fa" :class="copiedEmail ? 'fa-check' : 'fa-copy'"></i>
                            {{ copiedEmail ? 'Copiado' : 'Copiar' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fallo de carga: se distingue de "no hay plan" para no perder la venta -->
        <div v-else-if="loadError" class="loading-state">
            <i class="fa fa-exclamation-triangle load-error-icon"></i>
            <p class="text-muted">No pudimos cargar la informacion del plan.</p>
            <button class="btn-retry" @click="load">
                <i class="fa fa-redo me-1"></i> Reintentar
            </button>
        </div>

        <!-- Sin plan configurado -->
        <div v-else class="loading-state">
            <p class="text-muted">No hay planes disponibles en este momento.</p>
        </div>
    </div>
</template>

<script>
import api from '@/services/api.js';
import planService from '@/services/planService.js';
import subscriptionService from '@/services/subscriptionService.js';

export default {
    name: 'Plans',

    data() {
        return {
            plan: null,
            currentPlan: null,
            subscriptionStatus: null,
            loading: true,
            loadError: false,
            countdown: '',
            timer: null,
            // Se cargan de la configuracion: el Master puede cambiarlos desde el panel.
            supportEmail: '',
            supportWhatsapp: null,
            copiedEmail: false,
            copyTimer: null,
        };
    },

    computed: {
        isCurrentPlan() {
            return this.currentPlan && this.plan && this.currentPlan.id === this.plan.id;
        },

        // Estar en trial también "es" el plan actual, pero todavía debe poder pagar.
        isPaidActive() {
            return this.subscriptionStatus === 'active';
        },

        features() {
            return Array.isArray(this.plan?.features) ? this.plan.features : [];
        },

        whatsappLink() {
            if (!this.supportWhatsapp) return null;

            const texto = 'Hola, quiero cotizar NexosCard para mi equipo. '
                + '¿Me pueden dar precio por varias tarjetas?';

            return `https://api.whatsapp.com/send?phone=${this.supportWhatsapp}`
                + `&text=${encodeURIComponent(texto)}`;
        },
    },

    async created() {
        await this.load();
    },

    beforeUnmount() {
        // Sin esto el intervalo sigue corriendo tras salir de la vista.
        this.stopCountdown();
        if (this.copyTimer) clearTimeout(this.copyTimer);
    },

    methods: {
        async load() {
            this.loading = true;
            this.loadError = false;

            // allSettled y no all: el contacto de soporte y la suscripcion son datos
            // accesorios, y con Promise.all un fallo en cualquiera de ellos dejaba la
            // pagina vacia como si no existieran planes.
            const [plansRes, subRes, contactRes] = await Promise.allSettled([
                planService.all(),
                subscriptionService.current(),
                api.get('/dashboard'),
            ]);

            if (contactRes.status === 'fulfilled') {
                this.supportEmail = contactRes.value.data.contact?.support_email || '';
                this.supportWhatsapp = contactRes.value.data.contact?.support_whatsapp || null;
            }

            if (subRes.status === 'fulfilled') {
                this.currentPlan = subRes.value.data.plan || null;
                this.subscriptionStatus = subRes.value.data.subscription?.status || null;
            }

            // Solo el plan es indispensable: si esta llamada falla hay que decirlo, no
            // fingir que no hay nada que vender.
            if (plansRes.status === 'fulfilled') {
                // Producto único: se toma el plan por defecto, con el primero de fallback.
                const plans = plansRes.value.data || [];
                this.plan = plans.find(p => p.is_default) || plans[0] || null;
            } else {
                this.plan = null;
                this.loadError = true;
            }

            this.startCountdown();
            this.loading = false;
        },

        money(value) {
            const n = Number(value);
            if (!Number.isFinite(n)) return '$0';
            return '$' + n.toLocaleString('es-CO', { maximumFractionDigits: 0 });
        },

        startCountdown() {
            this.stopCountdown();

            if (!this.plan?.is_offer_active || !this.plan?.offer_ends_at) {
                this.countdown = '';
                return;
            }

            const tick = () => {
                const diff = new Date(this.plan.offer_ends_at) - new Date();

                if (diff <= 0) {
                    this.countdown = '';
                    this.stopCountdown();
                    // La oferta venció mientras la página estaba abierta: se recarga el
                    // plan para que el precio mostrado sea el que el servidor va a cobrar.
                    this.load();
                    return;
                }

                const d = Math.floor(diff / 86400000);
                const h = Math.floor((diff % 86400000) / 3600000);
                const m = Math.floor((diff % 3600000) / 60000);
                const s = Math.floor((diff % 60000) / 1000);

                this.countdown = d > 0 ? `${d}d ${h}h ${m}m` : `${h}h ${m}m ${s}s`;
            };

            tick();
            this.timer = setInterval(tick, 1000);
        },

        stopCountdown() {
            if (this.timer) {
                clearInterval(this.timer);
                this.timer = null;
            }
        },

        async copyEmail() {
            try {
                await navigator.clipboard.writeText(this.supportEmail);
            } catch {
                const el = document.createElement('textarea');
                el.value = this.supportEmail;
                document.body.appendChild(el);
                el.select();
                document.execCommand('copy');
                document.body.removeChild(el);
            }

            this.copiedEmail = true;
            if (this.copyTimer) clearTimeout(this.copyTimer);
            this.copyTimer = setTimeout(() => { this.copiedEmail = false; }, 2000);
        },
    },
};
</script>

<style scoped>
.offer-wrap {
    max-width: 560px;
    margin: 0 auto;
}

/* ===== Cinta de oferta ===== */
.offer-ribbon {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    background: linear-gradient(135deg, #dc2626, #ec4899);
    color: #ffffff;
    font-weight: 700;
    font-size: 0.9rem;
    letter-spacing: 0.02em;
    padding: 0.7rem 1rem;
    border-radius: 12px 12px 0 0;
    box-shadow: 0 6px 16px rgba(220, 38, 38, 0.25);
}

/* ===== Card ===== */
.plan-card {
    position: relative;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 0 0 16px 16px;
    padding: 2rem 1.75rem 1.75rem;
    box-shadow: 0 10px 30px rgba(15, 23, 42, 0.07);
}

.plan-card.current {
    border-color: #7c3aed;
}

.current-badge {
    position: absolute;
    top: -0.75rem;
    right: 1.25rem;
    background: #7c3aed;
    color: #ffffff;
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0.25rem 0.75rem;
    border-radius: 999px;
}

.plan-header {
    text-align: center;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid #e2e8f0;
}

.plan-name {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 0.5rem;
}

.plan-tagline {
    font-size: 0.9rem;
    color: #64748b;
    margin: 0 auto 1.5rem;
    max-width: 380px;
    line-height: 1.5;
}

/* ===== Precio ===== */
.price-block {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.35rem;
}

.price-before {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
    color: #94a3b8;
}

.before-label {
    text-transform: uppercase;
    font-size: 0.7rem;
    font-weight: 600;
    letter-spacing: 0.05em;
}

/* Tachado en rojo: el precio anterior debe leerse como descartado, no como el vigente */
.before-amount {
    position: relative;
    font-weight: 600;
    text-decoration: line-through;
    text-decoration-color: #dc2626;
    text-decoration-thickness: 2px;
}

.price-now {
    display: flex;
    align-items: baseline;
    gap: 0.4rem;
}

.now-label {
    text-transform: uppercase;
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.05em;
    color: #059669;
}

.now-amount {
    font-size: 3rem;
    font-weight: 800;
    line-height: 1;
    color: #059669;
}

.now-period {
    font-size: 1rem;
    font-weight: 500;
    color: #64748b;
}

.price-save {
    font-size: 0.85rem;
    font-weight: 600;
    color: #059669;
    background: #d1fae5;
    padding: 0.2rem 0.7rem;
    border-radius: 999px;
}

/* ===== Contador ===== */
.countdown {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    margin-top: 1.1rem;
    background: #fef2f2;
    color: #b91c1c;
    border: 1px solid #fecaca;
    font-size: 0.85rem;
    padding: 0.45rem 0.9rem;
    border-radius: 999px;
}

.countdown strong {
    font-variant-numeric: tabular-nums;
}

/* ===== Features ===== */
.plan-body {
    padding: 1.5rem 0;
}

.features-title {
    font-size: 0.85rem;
    font-weight: 600;
    color: #475569;
    margin: 0 0 0.9rem;
}

.plan-features {
    list-style: none;
    padding: 0;
    margin: 0;
    display: grid;
    gap: 0.6rem;
}

.plan-features li {
    display: flex;
    align-items: flex-start;
    gap: 0.6rem;
    font-size: 0.9rem;
    color: #334155;
    line-height: 1.4;
}

.plan-features i {
    color: #059669;
    font-size: 0.8rem;
    margin-top: 0.25rem;
    flex-shrink: 0;
}

/* ===== Acción ===== */
.plan-action {
    text-align: center;
}

.btn-plan {
    display: block;
    width: 100%;
    padding: 0.9rem 1rem;
    border-radius: 10px;
    border: none;
    font-size: 1rem;
    font-weight: 600;
    text-decoration: none;
    text-align: center;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-primary-cta {
    background: linear-gradient(135deg, #8b5cf6, #ec4899);
    color: #ffffff;
    box-shadow: 0 8px 20px rgba(139, 92, 246, 0.4);
}

.btn-primary-cta:hover {
    color: #ffffff;
    transform: translateY(-1px);
    box-shadow: 0 10px 24px rgba(139, 92, 246, 0.5);
}

.btn-current {
    background: #f1f5f9;
    color: #64748b;
    cursor: default;
}

.action-note {
    font-size: 0.78rem;
    color: #94a3b8;
    margin: 0.7rem 0 0;
}

/* ===== Nota de equipos ===== */
.team-note {
    display: flex;
    gap: 1rem;
    align-items: flex-start;
    margin-top: 1.25rem;
    padding: 1.1rem 1.25rem;
    background: #f5f3ff;
    border: 1px solid #ddd6fe;
    border-radius: 12px;
}

.team-icon {
    width: 40px;
    height: 40px;
    flex-shrink: 0;
    border-radius: 10px;
    background: #7c3aed;
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
}

.team-copy strong {
    display: block;
    font-size: 0.95rem;
    color: #1e293b;
    margin-bottom: 0.2rem;
}

.team-copy p {
    font-size: 0.85rem;
    color: #64748b;
    margin: 0 0 0.4rem;
    line-height: 1.5;
}

/* Boton de WhatsApp: accion real, no un enlace que puede no hacer nada */
.team-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    background: #25d366;
    color: #ffffff;
    font-size: 0.875rem;
    font-weight: 600;
    padding: 0.55rem 1rem;
    border-radius: 8px;
    text-decoration: none;
    box-shadow: 0 4px 12px rgba(37, 211, 102, 0.3);
    transition: all 0.2s ease;
}

.team-btn:hover {
    background: #1eb855;
    color: #ffffff;
    transform: translateY(-1px);
}

.team-btn i { font-size: 1rem; }

/* Sin numero configurado: al menos el correo se puede copiar */
.team-fallback {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
    color: #475569;
}

.btn-copy-mail {
    border: 1px solid #ddd6fe;
    background: #ffffff;
    color: #7c3aed;
    font-size: 0.78rem;
    font-weight: 600;
    padding: 0.3rem 0.6rem;
    border-radius: 6px;
    cursor: pointer;
}

.btn-copy-mail i { margin-right: 0.25rem; }
.btn-copy-mail.copied { color: #059669; border-color: #a7f3d0; }

/* ===== Loading ===== */
.loading-state {
    text-align: center;
    padding: 4rem 1rem;
}

.load-error-icon {
    font-size: 2rem;
    color: #f59e0b;
    margin-bottom: 0.75rem;
}

.btn-retry {
    border: 1px solid #ddd6fe;
    background: #ffffff;
    color: #7c3aed;
    font-size: 0.875rem;
    font-weight: 600;
    padding: 0.5rem 1.1rem;
    border-radius: 8px;
    cursor: pointer;
}

.btn-retry:hover {
    background: #f5f3ff;
}

/* ===== Responsive ===== */
@media (max-width: 576px) {
    .now-amount {
        font-size: 2.4rem;
    }

    .plan-card {
        padding: 1.5rem 1.15rem 1.25rem;
    }

    .team-note {
        flex-direction: column;
        gap: 0.75rem;
    }
}
</style>

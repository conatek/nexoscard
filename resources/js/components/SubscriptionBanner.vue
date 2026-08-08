<template>
    <div v-if="visible" class="sub-banner" :class="bannerClass">
        <div class="sub-banner-content">
            <div class="sub-banner-text">
                <i :class="bannerIcon" class="sub-banner-icon"></i>
                <span v-html="bannerMessage"></span>
            </div>
            <div class="sub-banner-actions">
                <router-link v-if="showUpgrade" :to="{ name: 'subscription.plans' }" class="sub-banner-btn">
                    {{ bannerButtonText }}
                </router-link>
                <button @click="dismiss" class="sub-banner-dismiss" title="Cerrar">
                    <i class="fa fa-times"></i>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import { useAuth } from '@/stores/auth';
import { useSubscription } from '@/stores/subscription';

export default {
    name: 'SubscriptionBanner',

    data() {
        // El estado vive en el store para que el checkout pueda refrescarlo tras un pago
        // aprobado: este componente se monta una vez y no se vuelve a crear al navegar.
        return {
            subscriptionState: useSubscription().state,
            dismissed: false,
        };
    },

    computed: {
        status() {
            return this.subscriptionState.status;
        },

        daysRemaining() {
            return this.subscriptionState.daysRemaining;
        },

        loaded() {
            return this.subscriptionState.loaded;
        },

        visible() {
            if (!this.loaded || this.dismissed) return false;
            const auth = useAuth();
            if (auth.isMaster()) return false;
            if (this.status === 'active') return false;
            // Sin estado no hay nada que decir. Antes se pintaba igual, y salia una
            // franja vacia sin mensaje ni boton.
            if (!this.status) return false;

            return true;
        },

        // Con un trial de 7 dias, el umbral de "<= 7" abarcaba todo el periodo y nunca
        // escalaba: critico el ultimo dia, urgente los 3 ultimos.
        bannerClass() {
            if (this.status === 'expired' || this.status === 'past_due') return 'banner-error';
            if (this.status === 'trial' && this.daysRemaining <= 1) return 'banner-urgent';
            if (this.status === 'trial' && this.daysRemaining <= 3) return 'banner-warning';
            return 'banner-info';
        },

        bannerIcon() {
            if (this.status === 'expired') return 'fa fa-times-circle';
            if (this.status === 'past_due') return 'fa fa-exclamation-circle';
            if (this.status === 'trial' && this.daysRemaining <= 1) return 'fa fa-clock';
            if (this.status === 'trial' && this.daysRemaining <= 3) return 'fa fa-exclamation-triangle';
            return 'fa fa-info-circle';
        },

        bannerMessage() {
            if (this.status === 'expired') {
                return 'Tu periodo ha expirado y tu tarjeta publica esta <strong>fuera de linea</strong>. Activa tu plan para volver a publicarla.';
            }
            if (this.status === 'past_due') {
                return 'Tu pago esta <strong>pendiente</strong>. Renueva para que tu tarjeta siga publicada.';
            }
            if (this.status === 'trial' && this.daysRemaining <= 0) {
                return 'Tu periodo de prueba ha <strong>finalizado</strong>. Tu tarjeta dejara de estar disponible.';
            }
            if (this.status === 'trial' && this.daysRemaining <= 1) {
                return 'Tu prueba vence <strong>hoy</strong>. Activa tu plan para que tu tarjeta siga en linea.';
            }
            if (this.status === 'trial' && this.daysRemaining <= 3) {
                return `Tu prueba vence en <strong>${this.daysRemaining} dias</strong>. Activa tu plan para no perder tu tarjeta publicada.`;
            }
            if (this.status === 'trial') {
                return `Periodo de prueba. Te quedan <strong>${this.daysRemaining} dias</strong>.`;
            }
            return '';
        },

        bannerButtonText() {
            if (this.status === 'expired' || this.status === 'past_due') return 'Renovar';
            if (this.status === 'trial' && this.daysRemaining <= 3) return 'Activar ahora';
            return 'Ver planes';
        },

        showUpgrade() {
            return ['trial', 'expired', 'past_due'].includes(this.status);
        },
    },

    watch: {
        // Si el estado cambia (por ejemplo al pagar), un banner cerrado a mano debe
        // volver a aparecer: el mensaje ya no es el mismo que se descartó.
        status(nuevo) {
            const descartado = sessionStorage.getItem('banner_dismissed');

            if (descartado && descartado !== nuevo) {
                this.dismissed = false;
                sessionStorage.removeItem('banner_dismissed');
            }
        },
    },

    async created() {
        const auth = useAuth();
        if (auth.isMaster()) return;

        if (sessionStorage.getItem('banner_dismissed')) {
            this.dismissed = true;
        }

        await useSubscription().refresh();
    },

    methods: {
        dismiss() {
            this.dismissed = true;
            sessionStorage.setItem('banner_dismissed', this.status);
        },
    },
};
</script>

<style scoped>
.sub-banner {
    padding: 0.625rem 1.25rem;
    border-bottom: 1px solid transparent;
    flex-shrink: 0;
}

.sub-banner-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    max-width: 100%;
}

.sub-banner-text {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
    line-height: 1.4;
    flex: 1;
    min-width: 0;
}

.sub-banner-icon {
    flex-shrink: 0;
    font-size: 0.9rem;
}

.sub-banner-actions {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-shrink: 0;
}

.sub-banner-btn {
    padding: 0.3rem 0.75rem;
    background: rgba(0, 0, 0, 0.08);
    color: inherit;
    border-radius: 6px;
    font-size: 0.78rem;
    font-weight: 600;
    text-decoration: none;
    white-space: nowrap;
    transition: all 0.2s;
}

.sub-banner-btn:hover {
    background: rgba(0, 0, 0, 0.14);
    color: inherit;
}

.sub-banner-dismiss {
    background: none;
    border: none;
    color: inherit;
    opacity: 0.5;
    cursor: pointer;
    padding: 0.25rem;
    font-size: 0.75rem;
}

.sub-banner-dismiss:hover {
    opacity: 0.8;
}

/* Variantes */
.banner-info {
    background: #ede9fe;
    color: #5b21b6;
    border-bottom-color: #ddd6fe;
}

.banner-warning {
    background: #fef3c7;
    color: #92400e;
    border-bottom-color: #fde68a;
}

.banner-urgent {
    background: #fee2e2;
    color: #991b1b;
    border-bottom-color: #fecaca;
}

.banner-error {
    background: #fef2f2;
    color: #991b1b;
    border-bottom-color: #fecaca;
}

@media (max-width: 768px) {
    .sub-banner {
        padding: 0.5rem 0.75rem;
    }

    .sub-banner-content {
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .sub-banner-text {
        font-size: 0.8rem;
    }
}
</style>

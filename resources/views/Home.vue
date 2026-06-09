<template>
    <div>
        <!-- Loading -->
        <div v-if="loading" class="loading-state">
            <div class="spinner-border text-primary"></div>
        </div>

        <template v-else>
            <!-- Saludo personalizado -->
            <div class="welcome-header">
                <div class="welcome-greeting">
                    <h1 class="greeting-text">
                        {{ greeting }}, <span class="gradient-text">{{ firstName }}</span>
                    </h1>
                    <p class="greeting-sub" v-if="data.subscription">
                        {{ planLabel }}
                    </p>
                </div>
            </div>

            <!-- Stats de la empresa -->
            <div v-if="data.stats" class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #8b5cf6, #a78bfa);">
                        <i class="fa fa-id-card"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-value">{{ data.stats.cards.current }}</span>
                        <span class="stat-label">
                            Tarjetas
                            <span v-if="data.stats.cards.limit" class="stat-limit">/ {{ data.stats.cards.limit }}</span>
                        </span>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #ec4899, #f472b6);">
                        <i class="fa fa-box"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-value">{{ data.stats.products.current }}</span>
                        <span class="stat-label">
                            Productos
                            <span v-if="data.stats.products.limit" class="stat-limit">/ {{ data.stats.products.limit }}</span>
                        </span>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #34d399);">
                        <i class="fa fa-concierge-bell"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-value">{{ data.stats.services.current }}</span>
                        <span class="stat-label">
                            Servicios
                            <span v-if="data.stats.services.limit" class="stat-limit">/ {{ data.stats.services.limit }}</span>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Acciones rapidas -->
            <div class="quick-section">
                <h2 class="section-title">Accesos rapidos</h2>
                <div class="quick-grid">
                    <router-link v-if="companyId" :to="`/empresas/${companyId}`" class="quick-card">
                        <div class="quick-icon" style="background: linear-gradient(135deg, #6366f1, #8b5cf6);">
                            <i class="fa fa-building"></i>
                        </div>
                        <div class="quick-text">
                            <h3>Mi Empresa</h3>
                            <p>Gestiona tarjetas, productos y servicios</p>
                        </div>
                        <i class="fa fa-chevron-right quick-arrow"></i>
                    </router-link>

                    <router-link v-if="companyId && data.stats?.cards.current > 0" :to="`/empresas/${companyId}/plantilla`" class="quick-card">
                        <div class="quick-icon" style="background: linear-gradient(135deg, #ec4899, #f97316);">
                            <i class="fa fa-palette"></i>
                        </div>
                        <div class="quick-text">
                            <h3>Personalizar Plantilla</h3>
                            <p>Ajusta el diseno de tus tarjetas</p>
                        </div>
                        <i class="fa fa-chevron-right quick-arrow"></i>
                    </router-link>

                    <router-link to="/mi-suscripcion" class="quick-card">
                        <div class="quick-icon" style="background: linear-gradient(135deg, #f59e0b, #fbbf24);">
                            <i class="fa fa-star"></i>
                        </div>
                        <div class="quick-text">
                            <h3>Mi Plan</h3>
                            <p>Revisa tu suscripcion y limites</p>
                        </div>
                        <i class="fa fa-chevron-right quick-arrow"></i>
                    </router-link>

                    <a v-if="publicUrl" :href="publicUrl" target="_blank" class="quick-card">
                        <div class="quick-icon" style="background: linear-gradient(135deg, #10b981, #34d399);">
                            <i class="fa fa-external-link-alt"></i>
                        </div>
                        <div class="quick-text">
                            <h3>Ver Tarjeta Publica</h3>
                            <p>{{ publicUrl }}</p>
                        </div>
                        <i class="fa fa-chevron-right quick-arrow"></i>
                    </a>
                </div>
            </div>

            <!-- Tip / Call to action -->
            <div v-if="showTip" class="tip-card">
                <div class="tip-icon">
                    <i class="fa fa-lightbulb"></i>
                </div>
                <div class="tip-content">
                    <h3>{{ tipTitle }}</h3>
                    <p>{{ tipMessage }}</p>
                </div>
                <router-link v-if="tipRoute" :to="tipRoute" class="tip-btn">
                    {{ tipAction }}
                </router-link>
            </div>
        </template>
    </div>
</template>

<script>
import { useAuth } from '@/stores/auth';
import api from '@/services/api.js';

export default {
    name: 'Home',

    data() {
        return {
            loading: true,
            data: {
                user: null,
                company: null,
                subscription: null,
                stats: null,
            },
        };
    },

    computed: {
        firstName() {
            const name = this.data.user?.name || '';
            return name.split(' ')[0];
        },

        greeting() {
            const hour = new Date().getHours();
            if (hour < 12) return 'Buenos dias';
            if (hour < 18) return 'Buenas tardes';
            return 'Buenas noches';
        },

        companyId() {
            return this.data.company?.id;
        },

        publicUrl() {
            const companySlug = this.data.company?.slug;
            const cardSlug = this.data.company?.first_card_slug;
            if (!companySlug || !cardSlug) return null;
            return `/${companySlug}/${cardSlug}`;
        },

        planLabel() {
            const sub = this.data.subscription;
            if (!sub) return '';
            if (sub.status === 'trial') return `Plan de prueba - ${sub.days_remaining} dias restantes`;
            if (sub.status === 'active') return `Plan ${sub.plan_name} activo`;
            if (sub.status === 'past_due') return 'Suscripcion vencida - renueva tu plan';
            if (sub.status === 'expired') return 'Periodo expirado';
            return '';
        },

        showTip() {
            const stats = this.data.stats;
            if (!stats) return false;
            return stats.cards.current === 0 || this.data.subscription?.status === 'trial';
        },

        tipTitle() {
            if (this.data.stats?.cards.current === 0) return 'Crea tu primera tarjeta';
            return 'Saca el maximo provecho';
        },

        tipMessage() {
            if (this.data.stats?.cards.current === 0) {
                return 'Aun no tienes tarjetas de presentacion. Crea una ahora y compartela con tus contactos.';
            }
            return 'Personaliza tu plantilla, agrega productos y servicios para que tu tarjeta sea mas completa.';
        },

        tipRoute() {
            if (this.data.stats?.cards.current === 0 && this.companyId) {
                return `/empresas/${this.companyId}`;
            }
            if (this.companyId) return `/empresas/${this.companyId}/plantilla`;
            return null;
        },

        tipAction() {
            if (this.data.stats?.cards.current === 0) return 'Crear tarjeta';
            return 'Personalizar';
        },
    },

    async created() {
        const auth = useAuth();
        if (auth.isMaster()) {
            this.$router.replace({ name: 'admin.dashboard' });
            return;
        }
        await this.load();
    },

    methods: {
        async load() {
            this.loading = true;
            try {
                const { data } = await api.get('/dashboard');
                this.data = data;
            } catch {
                // Silenciar
            } finally {
                this.loading = false;
            }
        },
    },
};
</script>

<style scoped>
/* Saludo */
.welcome-header {
    margin-bottom: 1.5rem;
}

.greeting-text {
    font-size: 1.75rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 0.25rem;
}

.gradient-text {
    background: linear-gradient(135deg, #8b5cf6, #ec4899);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.greeting-sub {
    font-size: 0.9rem;
    color: #64748b;
    margin: 0;
}

/* Stats */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
    margin-bottom: 1.75rem;
}

.stat-card {
    background: white;
    border-radius: 14px;
    padding: 1.25rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    border: 1px solid #e2e8f0;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.1rem;
    flex-shrink: 0;
}

.stat-value {
    display: block;
    font-size: 1.5rem;
    font-weight: 700;
    color: #1e293b;
    line-height: 1;
}

.stat-label {
    font-size: 0.8rem;
    color: #64748b;
}

.stat-limit {
    color: #94a3b8;
}

/* Acciones rapidas */
.section-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #1e293b;
    margin: 0 0 1rem;
}

.quick-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0.75rem;
    margin-bottom: 1.75rem;
}

.quick-card {
    background: white;
    border-radius: 14px;
    padding: 1.1rem 1.25rem;
    display: flex;
    align-items: center;
    gap: 0.875rem;
    border: 1px solid #e2e8f0;
    text-decoration: none;
    transition: all 0.2s;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
}

.quick-card:hover {
    border-color: #c4b5fd;
    box-shadow: 0 4px 16px rgba(139, 92, 246, 0.1);
    transform: translateY(-1px);
}

.quick-icon {
    width: 42px;
    height: 42px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1rem;
    flex-shrink: 0;
}

.quick-text {
    flex: 1;
    min-width: 0;
}

.quick-text h3 {
    font-size: 0.9rem;
    font-weight: 600;
    color: #1e293b;
    margin: 0 0 0.15rem;
}

.quick-text p {
    font-size: 0.78rem;
    color: #94a3b8;
    margin: 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.quick-arrow {
    color: #cbd5e1;
    font-size: 0.75rem;
    flex-shrink: 0;
}

/* Tip */
.tip-card {
    background: linear-gradient(135deg, #ede9fe, #fce7f3);
    border: 1px solid #ddd6fe;
    border-radius: 14px;
    padding: 1.25rem 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.tip-icon {
    width: 42px;
    height: 42px;
    border-radius: 10px;
    background: white;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #f59e0b;
    font-size: 1.1rem;
    flex-shrink: 0;
}

.tip-content {
    flex: 1;
}

.tip-content h3 {
    font-size: 0.9rem;
    font-weight: 600;
    color: #1e293b;
    margin: 0 0 0.2rem;
}

.tip-content p {
    font-size: 0.82rem;
    color: #64748b;
    margin: 0;
    line-height: 1.4;
}

.tip-btn {
    padding: 0.45rem 1rem;
    background: linear-gradient(135deg, #8b5cf6, #ec4899);
    color: white;
    font-weight: 600;
    font-size: 0.8rem;
    border-radius: 8px;
    text-decoration: none;
    white-space: nowrap;
    transition: all 0.2s;
}

.tip-btn:hover {
    transform: translateY(-1px);
    color: white;
    box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
}

.loading-state {
    text-align: center;
    padding: 4rem 0;
}

/* Responsive */
@media (max-width: 768px) {
    .greeting-text {
        font-size: 1.4rem;
    }

    .stats-grid {
        grid-template-columns: 1fr;
    }

    .quick-grid {
        grid-template-columns: 1fr;
    }

    .tip-card {
        flex-direction: column;
        text-align: center;
        gap: 0.75rem;
    }
}
</style>

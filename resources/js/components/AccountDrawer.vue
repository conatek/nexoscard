<template>
    <div class="drawer">
        <!-- Cabecera con pestañas -->
        <div class="drawer-head">
            <div class="drawer-tabs">
                <button
                    class="drawer-tab"
                    :class="{ active: tab === 'share' }"
                    @click="tab = 'share'"
                >
                    <i class="fa fa-share-alt"></i> Compartir
                </button>
                <button
                    class="drawer-tab"
                    :class="{ active: tab === 'account' }"
                    @click="tab = 'account'"
                >
                    <i class="fa fa-user"></i> Cuenta
                </button>
            </div>
            <button class="drawer-close" @click="$emit('close')" aria-label="Cerrar">
                <i class="fa fa-times"></i>
            </button>
        </div>

        <div class="drawer-body">
            <div v-if="loading" class="drawer-loading">
                <div class="spinner-border text-primary"></div>
            </div>

            <!-- ============ COMPARTIR ============ -->
            <template v-else-if="tab === 'share'">
                <template v-if="publicUrl">
                    <p class="drawer-lead">
                        Comparte tu tarjeta digital. Quien la escanee o abra el enlace
                        vera tu informacion siempre actualizada.
                    </p>

                    <div class="qr-frame">
                        <img :src="qrUrl" alt="Codigo QR de tu tarjeta" class="qr-img" />
                    </div>

                    <div class="url-box">
                        <span class="url-text">{{ shortUrl }}</span>
                        <button class="btn-copy" :class="{ copied }" @click="copyLink">
                            <i class="fa" :class="copied ? 'fa-check' : 'fa-copy'"></i>
                            {{ copied ? 'Copiado' : 'Copiar' }}
                        </button>
                    </div>

                    <div class="share-grid">
                        <a :href="waLink" target="_blank" rel="noopener" class="share-btn share-wa">
                            <i class="fab fa-whatsapp"></i>
                            <span>WhatsApp</span>
                        </a>
                        <a :href="mailLink" class="share-btn share-mail">
                            <i class="fa fa-envelope"></i>
                            <span>Email</span>
                        </a>
                        <a :href="publicUrl" target="_blank" rel="noopener" class="share-btn share-open">
                            <i class="fa fa-external-link-alt"></i>
                            <span>Abrir</span>
                        </a>
                        <button class="share-btn share-qr" @click="downloadQr">
                            <i class="fa fa-download"></i>
                            <span>Bajar QR</span>
                        </button>
                    </div>
                </template>

                <!-- Sin tarjeta todavia -->
                <div v-else class="empty-state">
                    <div class="empty-icon"><i class="fa fa-id-card"></i></div>
                    <h4>Aun no tienes tarjeta</h4>
                    <p>Crea tu primera tarjeta para poder compartirla por enlace o codigo QR.</p>
                    <router-link
                        v-if="companyId"
                        :to="`/empresas/${companyId}`"
                        class="btn-drawer-cta"
                        @click="$emit('close')"
                    >
                        <i class="fa fa-plus me-1"></i> Crear tarjeta
                    </router-link>
                </div>
            </template>

            <!-- ============ CUENTA ============ -->
            <template v-else>
                <div class="account-head">
                    <div class="plan-chip">{{ subscription?.plan_name || 'Sin plan' }}</div>
                    <span class="status-pill" :class="'st-' + (subscription?.status || 'none')">
                        {{ statusLabel }}
                    </span>
                </div>

                <!-- Anillo de dias restantes: solo mientras hay cuenta regresiva -->
                <div v-if="showCountdown" class="ring-wrap">
                    <svg class="ring" viewBox="0 0 120 120">
                        <circle class="ring-bg" cx="60" cy="60" r="52" />
                        <circle
                            class="ring-fill"
                            :class="ringClass"
                            cx="60" cy="60" r="52"
                            :stroke-dasharray="ringCircumference"
                            :stroke-dashoffset="ringOffset"
                        />
                    </svg>
                    <div class="ring-center">
                        <span class="ring-value">{{ daysRemaining }}</span>
                        <span class="ring-label">{{ daysRemaining === 1 ? 'dia' : 'dias' }}</span>
                    </div>
                </div>

                <p v-if="periodLabel" class="period-label">{{ periodLabel }}</p>

                <router-link
                    v-if="showActivateCta"
                    :to="{ name: 'subscription.plans' }"
                    class="btn-drawer-cta"
                    @click="$emit('close')"
                >
                    <i class="fa fa-bolt me-1"></i> {{ activateLabel }}
                </router-link>

                <!-- Uso de recursos -->
                <div class="usage-block">
                    <h5 class="block-title">Uso de tu plan</h5>
                    <div v-for="(res, key) in stats" :key="key" class="usage-row">
                        <div class="usage-head">
                            <span>{{ resourceNames[key] }}</span>
                            <span class="usage-count" :class="{ 'is-full': isFull(res) }">
                                {{ res.current }} / {{ res.limit ?? '∞' }}
                            </span>
                        </div>
                        <!-- Sin limite la barra no significa nada: se omite en vez de
                             pintar un porcentaje enganoso -->
                        <div v-if="res.limit" class="usage-track">
                            <div
                                class="usage-fill"
                                :class="{ 'is-full': isFull(res) }"
                                :style="{ width: usagePercent(res) + '%' }"
                            ></div>
                        </div>
                        <div v-else class="usage-unlimited">Sin limite</div>
                    </div>
                </div>

                <a
                    v-if="supportLink"
                    :href="supportLink"
                    :target="supportWhatsapp ? '_blank' : null"
                    rel="noopener"
                    class="support-link"
                    :class="{ 'is-wa': supportWhatsapp }"
                >
                    <i class="fa" :class="supportWhatsapp ? 'fab fa-whatsapp' : 'fa-life-ring'"></i>
                    <div>
                        <strong>¿Necesitas ayuda?</strong>
                        <span>{{ supportWhatsapp ? 'Escribenos por WhatsApp' : supportEmail }}</span>
                    </div>
                    <i class="fa fa-chevron-right"></i>
                </a>
            </template>
        </div>
    </div>
</template>

<script>
import api from '@/services/api.js';

export default {
    name: 'AccountDrawer',

    emits: ['close'],

    data() {
        return {
            tab: 'share',
            loading: true,
            company: null,
            subscription: null,
            stats: null,
            copied: false,
            copyTimer: null,
            // Vienen de la configuracion editable por el Master, no hardcodeados.
            supportEmail: '',
            supportWhatsapp: null,
            resourceNames: {
                cards: 'Tarjetas',
                products: 'Productos',
                services: 'Servicios',
            },
        };
    },

    computed: {
        companyId() {
            return this.company?.id;
        },

        publicUrl() {
            if (!this.company?.slug || !this.company?.first_card_slug) return null;
            return `${window.location.origin}/${this.company.slug}/${this.company.first_card_slug}`;
        },

        // Sin el protocolo: el enlace completo no entra en 500px sin romperse feo
        shortUrl() {
            return this.publicUrl ? this.publicUrl.replace(/^https?:\/\//, '') : '';
        },

        qrUrl() {
            if (!this.publicUrl) return '';
            return 'https://api.qrserver.com/v1/create-qr-code/?size=400x400&margin=8&data='
                + encodeURIComponent(this.publicUrl);
        },

        waLink() {
            const text = `Hola, esta es mi tarjeta digital: ${this.publicUrl}`;
            return `https://api.whatsapp.com/send?text=${encodeURIComponent(text)}`;
        },

        mailLink() {
            const subject = encodeURIComponent('Mi tarjeta digital');
            const body = encodeURIComponent(`Hola,\n\nEsta es mi tarjeta digital:\n${this.publicUrl}`);
            return `mailto:?subject=${subject}&body=${body}`;
        },

        statusLabel() {
            const labels = {
                trial: 'Prueba',
                active: 'Activo',
                past_due: 'Pago pendiente',
                cancelled: 'Cancelado',
                expired: 'Expirado',
            };
            return labels[this.subscription?.status] || 'Sin suscripcion';
        },

        daysRemaining() {
            return this.subscription?.days_remaining ?? 0;
        },

        showCountdown() {
            return ['trial', 'active', 'past_due'].includes(this.subscription?.status);
        },

        // El anillo se llena sobre 7 dias en prueba y sobre 365 en un plan pagado.
        ringCircumference() {
            return 2 * Math.PI * 52;
        },

        ringOffset() {
            const total = this.subscription?.status === 'trial' ? 7 : 365;
            const pct = Math.min(1, Math.max(0, this.daysRemaining / total));
            return this.ringCircumference * (1 - pct);
        },

        ringClass() {
            if (this.subscription?.status !== 'trial') return 'ring-ok';
            if (this.daysRemaining <= 1) return 'ring-danger';
            if (this.daysRemaining <= 3) return 'ring-warn';
            return 'ring-ok';
        },

        periodLabel() {
            if (!this.subscription?.period_end) return '';

            const fecha = new Date(this.subscription.period_end).toLocaleDateString('es-CO', {
                day: 'numeric', month: 'long', year: 'numeric',
            });

            if (this.subscription.status === 'trial') return `Tu prueba termina el ${fecha}`;
            if (this.subscription.status === 'active') return `Se renueva el ${fecha}`;
            return `Vencio el ${fecha}`;
        },

        showActivateCta() {
            return ['trial', 'past_due', 'expired', 'cancelled'].includes(this.subscription?.status)
                || !this.subscription;
        },

        // Prefiere WhatsApp; cae a mailto solo si no hay numero configurado.
        supportLink() {
            if (this.supportWhatsapp) {
                const texto = 'Hola, necesito ayuda con mi cuenta de NexosCard.';
                return `https://api.whatsapp.com/send?phone=${this.supportWhatsapp}`
                    + `&text=${encodeURIComponent(texto)}`;
            }

            return this.supportEmail ? `mailto:${this.supportEmail}` : null;
        },

        activateLabel() {
            const price = Number(this.subscription?.plan_price);

            if (!Number.isFinite(price) || price <= 0) return 'Ver plan';

            return `Activar por $${price.toLocaleString('es-CO', { maximumFractionDigits: 0 })}`;
        },
    },

    async created() {
        await this.load();
    },

    beforeUnmount() {
        if (this.copyTimer) clearTimeout(this.copyTimer);
    },

    methods: {
        async load() {
            this.loading = true;
            try {
                const { data } = await api.get('/dashboard');
                this.company = data.company;
                this.subscription = data.subscription;
                this.stats = data.stats;
                this.supportEmail = data.contact?.support_email || '';
                this.supportWhatsapp = data.contact?.support_whatsapp || null;
            } catch {
                this.company = null;
            } finally {
                this.loading = false;
            }
        },

        async copyLink() {
            try {
                await navigator.clipboard.writeText(this.publicUrl);
            } catch {
                // Sin permiso de portapapeles (o contexto no seguro): se selecciona
                // el texto para que el usuario copie a mano.
                const el = document.createElement('textarea');
                el.value = this.publicUrl;
                document.body.appendChild(el);
                el.select();
                document.execCommand('copy');
                document.body.removeChild(el);
            }

            this.copied = true;
            if (this.copyTimer) clearTimeout(this.copyTimer);
            this.copyTimer = setTimeout(() => { this.copied = false; }, 2000);
        },

        downloadQr() {
            // El QR viene de un servicio externo: se abre en pestaña nueva para que el
            // usuario lo guarde (un <a download> no funciona entre dominios).
            window.open(this.qrUrl, '_blank', 'noopener');
        },

        usagePercent(res) {
            if (!res.limit) return 0;
            return Math.min(100, (res.current / res.limit) * 100);
        },

        isFull(res) {
            return res.limit !== null && res.current >= res.limit;
        },
    },
};
</script>

<style scoped>
.drawer {
    display: flex;
    flex-direction: column;
    height: 100vh;
    background: #ffffff;
}

/* ===== Cabecera ===== */
.drawer-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.5rem;
    padding: 0.9rem 1rem;
    border-bottom: 1px solid #e2e8f0;
    background: #f8fafc;
    flex-shrink: 0;
}

.drawer-tabs {
    display: flex;
    gap: 0.25rem;
    background: #f1f5f9;
    padding: 0.25rem;
    border-radius: 10px;
}

.drawer-tab {
    border: none;
    background: transparent;
    color: #64748b;
    font-size: 0.875rem;
    font-weight: 600;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.drawer-tab i { margin-right: 0.35rem; }

.drawer-tab.active {
    background: #ffffff;
    color: #7c3aed;
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.1);
}

.drawer-close {
    width: 34px;
    height: 34px;
    border: none;
    background: transparent;
    color: #94a3b8;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.drawer-close:hover { background: #f1f5f9; color: #475569; }

/* ===== Cuerpo ===== */
.drawer-body {
    flex: 1;
    overflow-y: auto;
    padding: 1.25rem;
}

.drawer-loading { text-align: center; padding: 4rem 0; }

.drawer-lead {
    font-size: 0.875rem;
    color: #64748b;
    line-height: 1.5;
    margin: 0 0 1.25rem;
}

/* ===== QR ===== */
.qr-frame {
    display: flex;
    justify-content: center;
    padding: 1rem;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    margin-bottom: 1rem;
}

.qr-img {
    width: 220px;
    height: 220px;
    display: block;
    border-radius: 8px;
    background: #ffffff;
}

/* ===== Enlace ===== */
.url-box {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 0.5rem 0.5rem 0.5rem 0.85rem;
    margin-bottom: 1rem;
}

.url-text {
    flex: 1;
    min-width: 0;
    font-size: 0.85rem;
    color: #334155;
    font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.btn-copy {
    flex-shrink: 0;
    border: none;
    background: #7c3aed;
    color: #ffffff;
    font-size: 0.8rem;
    font-weight: 600;
    padding: 0.45rem 0.8rem;
    border-radius: 8px;
    cursor: pointer;
    transition: background 0.2s ease;
}

.btn-copy i { margin-right: 0.3rem; }
.btn-copy:hover { background: #6d28d9; }
.btn-copy.copied { background: #059669; }

/* ===== Botones de compartir ===== */
.share-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0.6rem;
}

.share-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.7rem 0.5rem;
    border-radius: 10px;
    border: 1px solid #e2e8f0;
    background: #ffffff;
    color: #334155;
    font-size: 0.85rem;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.2s ease;
}

.share-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 10px rgba(15, 23, 42, 0.08);
}

.share-wa:hover { border-color: #25d366; color: #128c3e; }
.share-mail:hover { border-color: #dc2626; color: #b91c1c; }
.share-open:hover,
.share-qr:hover { border-color: #7c3aed; color: #6d28d9; }

/* ===== Estado vacio ===== */
.empty-state {
    text-align: center;
    padding: 3rem 1rem;
}

.empty-icon {
    width: 64px;
    height: 64px;
    margin: 0 auto 1rem;
    border-radius: 16px;
    background: #f5f3ff;
    color: #7c3aed;
    font-size: 1.6rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.empty-state h4 {
    font-size: 1.05rem;
    font-weight: 600;
    color: #1e293b;
    margin: 0 0 0.4rem;
}

.empty-state p {
    font-size: 0.875rem;
    color: #64748b;
    margin: 0 0 1.25rem;
    line-height: 1.5;
}

/* ===== Cuenta ===== */
.account-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.5rem;
    margin-bottom: 1.25rem;
}

.plan-chip {
    background: linear-gradient(135deg, #f3e8ff, #fce7f3);
    color: #7c3aed;
    font-weight: 700;
    font-size: 0.95rem;
    padding: 0.4rem 0.9rem;
    border-radius: 8px;
}

.status-pill {
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0.25rem 0.65rem;
    border-radius: 999px;
}

.st-trial { background: #fef3c7; color: #92400e; }
.st-active { background: #d1fae5; color: #065f46; }
.st-past_due { background: #fee2e2; color: #b91c1c; }
.st-expired,
.st-cancelled,
.st-none { background: #f1f5f9; color: #64748b; }

/* ===== Anillo ===== */
.ring-wrap {
    position: relative;
    width: 150px;
    height: 150px;
    margin: 0 auto 0.75rem;
}

.ring {
    width: 100%;
    height: 100%;
    transform: rotate(-90deg);
}

.ring-bg,
.ring-fill {
    fill: none;
    stroke-width: 9;
}

.ring-bg { stroke: #e2e8f0; }

.ring-fill {
    stroke-linecap: round;
    transition: stroke-dashoffset 0.5s ease;
}

.ring-ok { stroke: #7c3aed; }
.ring-warn { stroke: #d97706; }
.ring-danger { stroke: #dc2626; }

.ring-center {
    position: absolute;
    inset: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.ring-value {
    font-size: 2.4rem;
    font-weight: 800;
    color: #1e293b;
    line-height: 1;
}

.ring-label {
    font-size: 0.8rem;
    color: #64748b;
}

.period-label {
    text-align: center;
    font-size: 0.85rem;
    color: #64748b;
    margin: 0 0 1.25rem;
}

/* ===== CTA ===== */
.btn-drawer-cta {
    display: block;
    width: 100%;
    text-align: center;
    padding: 0.8rem 1rem;
    border-radius: 10px;
    background: linear-gradient(135deg, #8b5cf6, #ec4899);
    color: #ffffff;
    font-weight: 600;
    font-size: 0.95rem;
    text-decoration: none;
    box-shadow: 0 6px 16px rgba(139, 92, 246, 0.35);
    transition: all 0.2s ease;
}

.btn-drawer-cta:hover {
    color: #ffffff;
    transform: translateY(-1px);
    box-shadow: 0 8px 20px rgba(139, 92, 246, 0.45);
}

/* ===== Uso ===== */
.usage-block { margin: 1.5rem 0; }

.block-title {
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    color: #94a3b8;
    margin: 0 0 0.75rem;
}

.usage-row { margin-bottom: 0.85rem; }

.usage-head {
    display: flex;
    justify-content: space-between;
    font-size: 0.85rem;
    color: #334155;
    margin-bottom: 0.35rem;
}

.usage-count { font-weight: 600; color: #64748b; }
.usage-count.is-full { color: #dc2626; }

.usage-track {
    height: 6px;
    background: #f1f5f9;
    border-radius: 999px;
    overflow: hidden;
}

.usage-fill {
    height: 100%;
    background: #7c3aed;
    border-radius: 999px;
    transition: width 0.4s ease;
}

.usage-fill.is-full { background: #dc2626; }

.usage-unlimited {
    font-size: 0.75rem;
    color: #94a3b8;
}

/* ===== Soporte ===== */
.support-link {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.9rem 1rem;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    text-decoration: none;
    color: #334155;
    transition: all 0.2s ease;
}

.support-link:hover {
    background: #f5f3ff;
    border-color: #ddd6fe;
    color: #334155;
}

.support-link > i:first-child {
    color: #7c3aed;
    font-size: 1.1rem;
}

.support-link.is-wa > i:first-child { color: #25d366; }

.support-link.is-wa:hover {
    background: #f0fdf4;
    border-color: #bbf7d0;
}

.support-link div {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.support-link strong { font-size: 0.9rem; }

.support-link span {
    font-size: 0.8rem;
    color: #64748b;
}

.support-link > i:last-child {
    color: #cbd5e1;
    font-size: 0.75rem;
}
</style>

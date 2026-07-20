<template>
    <div>
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="fa fa-credit-card icon-gradient bg-mean-fruit"></i>
                    </div>
                    <div>
                        Confirmar Plan
                        <div class="page-title-subheading text-muted">
                            Revisa los detalles y completa el pago
                        </div>
                    </div>
                </div>
                <div class="page-title-actions">
                    <router-link :to="{ name: 'subscription.plans' }" class="btn-action btn-back">
                        <i class="fa fa-arrow-left me-1"></i> Volver
                    </router-link>
                </div>
            </div>
        </div>

        <!-- Loading plan -->
        <div v-if="loadingPlan" class="loading-state">
            <div class="spinner-border text-primary"></div>
            <p class="text-muted mt-3">Cargando plan...</p>
        </div>

        <!-- Checkout content -->
        <div v-else-if="plan" class="checkout-container">
            <!-- Resumen del pedido -->
            <div class="checkout-card">
                <div class="checkout-header">
                    <h3>Resumen del pedido</h3>
                </div>
                <div class="checkout-body">
                    <div class="checkout-row">
                        <span class="label">Plan</span>
                        <span class="value">{{ plan.display_name }}</span>
                    </div>
                    <div class="checkout-row">
                        <span class="label">Periodo</span>
                        <span class="value">Anual &middot; 12 meses</span>
                    </div>
                    <div v-if="plan.is_offer_active" class="checkout-row">
                        <span class="label">Precio normal</span>
                        <span class="value struck">${{ money(plan.price_regular) }}</span>
                    </div>
                    <div v-if="plan.is_offer_active" class="checkout-row">
                        <span class="label">Descuento</span>
                        <span class="value discount">
                            &minus;${{ money(plan.price_regular - plan.effective_price) }}
                            ({{ plan.discount_percent }}%)
                        </span>
                    </div>
                    <div class="checkout-divider"></div>
                    <div class="checkout-row total">
                        <span class="label">Total</span>
                        <span class="value">${{ formatPrice }} COP</span>
                    </div>
                </div>
            </div>

            <!-- Resultado del pago -->
            <div v-if="paymentResult" class="result-card" :class="'result-' + paymentResult.status">
                <div class="result-icon">
                    <i v-if="paymentResult.status === 'approved'" class="fa fa-check-circle"></i>
                    <i v-else-if="paymentResult.status === 'pending'" class="fa fa-clock"></i>
                    <i v-else class="fa fa-times-circle"></i>
                </div>
                <h3 class="result-title">{{ resultTitle }}</h3>
                <p class="result-message">{{ resultMessage }}</p>
                <router-link v-if="paymentResult.status === 'approved'" :to="{ name: 'home' }" class="btn-result">
                    <i class="fa fa-home me-2"></i> Ir al panel
                </router-link>
                <button v-else-if="paymentResult.status === 'declined'" class="btn-result btn-retry" @click="resetPayment">
                    <i class="fa fa-redo me-2"></i> Intentar de nuevo
                </button>
            </div>

            <!-- Formulario de pago (Brick) -->
            <div v-show="!paymentResult" class="checkout-card payment-card">
                <div class="checkout-header">
                    <h3>Datos de pago</h3>
                </div>
                <div class="checkout-body">
                    <div v-if="error" class="error-alert">
                        <i class="fa fa-exclamation-circle"></i>
                        {{ error }}
                    </div>
                    <div id="cardPaymentBrick_container"></div>
                    <div v-if="loadingBrick" class="loading-brick">
                        <div class="spinner-border spinner-border-sm text-primary"></div>
                        <span class="text-muted ms-2">Cargando formulario de pago...</span>
                    </div>
                </div>
                <div class="checkout-footer">
                    <p class="checkout-note">
                        <i class="fa fa-shield-alt me-1"></i>
                        Pago seguro procesado por MercadoPago. Tus datos estan protegidos.
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import planService from '@/services/planService.js';
import paymentService from '@/services/paymentService.js';

export default {
    name: 'Checkout',

    data() {
        return {
            plan: null,
            loadingPlan: true,
            loadingBrick: true,
            processing: false,
            error: null,
            paymentResult: null,
            brickController: null,
        };
    },

    computed: {
        formatPrice() {
            return this.money(this.totalAmount);
        },
        // El precio efectivo lo resuelve el servidor (oferta vigente o precio regular);
        // aquí solo se muestra y se reenvía como `expected_amount` para que el backend
        // aborte si cambió mientras se llenaba el formulario.
        totalAmount() {
            return this.plan ? Number(this.plan.effective_price) : 0;
        },
        resultTitle() {
            if (!this.paymentResult) return '';
            const map = { approved: 'Pago aprobado', pending: 'Pago pendiente', declined: 'Pago no aprobado' };
            return map[this.paymentResult.status] || 'Estado desconocido';
        },
        resultMessage() {
            if (!this.paymentResult) return '';
            const map = {
                approved: 'Tu plan ha sido activado exitosamente. Ya puedes disfrutar de todas las funcionalidades.',
                pending: 'Tu pago esta siendo procesado. Te notificaremos cuando se confirme.',
                declined: 'Tu pago no pudo ser procesado. Puedes intentar nuevamente.',
            };
            return map[this.paymentResult.status] || 'No pudimos determinar el estado de tu pago.';
        },
    },

    async created() {
        await this.loadPlan();
    },

    beforeUnmount() {
        if (this.brickController) {
            this.brickController.unmount();
        }
    },

    methods: {
        money(value) {
            const n = Number(value);
            if (!Number.isFinite(n)) return '0';
            return n.toLocaleString('es-CO', { maximumFractionDigits: 0 });
        },

        async loadPlan() {
            this.loadingPlan = true;
            try {
                const { data } = await planService.show(this.$route.params.planId);
                this.plan = data;
                this.$nextTick(() => this.initBrick());
            } catch {
                this.error = 'Plan no encontrado.';
            } finally {
                this.loadingPlan = false;
            }
        },

        async initBrick() {
            if (!window.MercadoPago) {
                this.error = 'Error al cargar MercadoPago SDK.';
                this.loadingBrick = false;
                return;
            }

            const mp = new window.MercadoPago(window.mercadoPagoPublicKey, { locale: 'es-CO' });
            const bricksBuilder = mp.bricks();

            try {
                this.brickController = await bricksBuilder.create('cardPayment', 'cardPaymentBrick_container', {
                    initialization: {
                        amount: this.totalAmount,
                    },
                    customization: {
                        visual: {
                            style: {
                                theme: 'default',
                            },
                        },
                        paymentMethods: {
                            maxInstallments: 12,
                        },
                    },
                    callbacks: {
                        onReady: () => {
                            this.loadingBrick = false;
                        },
                        onSubmit: async (cardFormData) => {
                            await this.handlePayment(cardFormData);
                        },
                        onError: (error) => {
                            console.error('Brick error:', error);
                        },
                    },
                });
            } catch (err) {
                console.error('Error creating Brick:', err);
                this.error = 'Error al inicializar el formulario de pago.';
                this.loadingBrick = false;
            }
        },

        async handlePayment(cardFormData) {
            this.processing = true;
            this.error = null;

            try {
                const { data } = await paymentService.process({
                    plan_id:               this.plan.id,
                    // Precio que el usuario tenía en pantalla: el servidor rechaza el
                    // cobro si la oferta venció mientras llenaba el formulario.
                    expected_amount:       this.totalAmount,
                    token:                 cardFormData.token,
                    payment_method_id:     cardFormData.payment_method_id,
                    installments:          cardFormData.installments,
                    issuer_id:             cardFormData.issuer_id,
                    payer_email:           cardFormData.payer?.email || '',
                    identification_type:   cardFormData.payer?.identification?.type || 'CC',
                    identification_number: cardFormData.payer?.identification?.number || '',
                });

                this.paymentResult = data;
            } catch (err) {
                this.error = err.response?.data?.message || 'Error al procesar el pago.';
            } finally {
                this.processing = false;
            }
        },

        resetPayment() {
            this.paymentResult = null;
            this.error = null;
        },
    },
};
</script>

<style scoped>
.checkout-container {
    max-width: 520px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
}

.checkout-card {
    background: white;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
    overflow: hidden;
}

.checkout-header {
    padding: 1.25rem 1.5rem;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
}

.checkout-header h3 {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 600;
    color: #1e293b;
}

.checkout-body {
    padding: 1.5rem;
}

.checkout-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
}

.checkout-row .label {
    color: #64748b;
    font-size: 0.95rem;
}

.checkout-row .value {
    font-weight: 600;
    color: #1e293b;
}

/* Precio anterior: tachado en rojo para que se lea como descartado */
.checkout-row .value.struck {
    color: #94a3b8;
    font-weight: 500;
    text-decoration: line-through;
    text-decoration-color: #dc2626;
}

.checkout-row .value.discount {
    color: #059669;
}

.checkout-row.total .value {
    font-size: 1.25rem;
    color: #7c3aed;
}

.checkout-divider {
    height: 1px;
    background: #e2e8f0;
    margin: 0.5rem 0;
}

.checkout-footer {
    padding: 1rem 1.5rem;
    border-top: 1px solid #e2e8f0;
}

.checkout-note {
    text-align: center;
    font-size: 0.8rem;
    color: #94a3b8;
    margin: 0;
}

.error-alert {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1rem;
    background: #fef2f2;
    border: 1px solid #fecaca;
    border-radius: 8px;
    color: #dc2626;
    font-size: 0.9rem;
    margin-bottom: 1rem;
}

.loading-brick {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem 0;
}

/* Resultado */
.result-card {
    text-align: center;
    background: white;
    border-radius: 16px;
    padding: 2.5rem 2rem;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
}

.result-icon {
    font-size: 3.5rem;
    margin-bottom: 1rem;
}

.result-approved .result-icon { color: #10b981; }
.result-pending .result-icon  { color: #f59e0b; }
.result-declined .result-icon { color: #ef4444; }

.result-title {
    font-size: 1.4rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.5rem;
}

.result-message {
    font-size: 0.95rem;
    color: #64748b;
    line-height: 1.6;
    margin-bottom: 1.5rem;
}

.btn-result {
    display: inline-flex;
    align-items: center;
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s;
    background: linear-gradient(135deg, #10b981, #34d399);
    color: white;
    border: none;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

.btn-result:hover {
    transform: translateY(-1px);
    color: white;
}

.btn-retry {
    background: linear-gradient(135deg, #f59e0b, #fbbf24);
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
}

.loading-state {
    text-align: center;
    padding: 4rem 0;
}

.btn-action.btn-back {
    padding: 0.5rem 1rem;
    background: #f1f5f9;
    color: #475569;
    border-radius: 8px;
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 500;
    transition: all 0.2s;
    border: 1px solid #e2e8f0;
}

.btn-action.btn-back:hover {
    background: #e2e8f0;
}
</style>

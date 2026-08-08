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
                <!-- Un pago rechazado se reintenta; cualquier otro desenlace (aprobado,
                     pendiente o un estado inesperado) sale al panel. Antes el estado
                     pendiente se quedaba sin ningun boton y dejaba al usuario encerrado
                     en la pantalla de resultado. -->
                <button v-if="paymentResult.status === 'declined'" class="btn-result btn-retry" @click="resetPayment">
                    <i class="fa fa-redo me-2"></i> {{ retryLabel }}
                </button>
                <router-link v-else :to="{ name: 'home' }" class="btn-result">
                    <i class="fa fa-home me-2"></i> Ir al panel
                </router-link>
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
import { useSubscription } from '@/stores/subscription';

/**
 * Motivos de rechazo de MercadoPago (`status_detail`) traducidos a algo accionable.
 *
 * Todos los rechazos mostraban el mismo "no pudo ser procesado", aunque el gateway dice
 * exactamente que paso. No es lo mismo "no te alcanza el cupo" que "escribiste mal el
 * CVV": llevan a acciones distintas, y es la causa numero uno de tickets de soporte en
 * pagos con tarjeta.
 *
 * `reintentable` distingue el error que se corrige en el mismo formulario del que exige
 * otra tarjeta o una llamada al banco, para no invitar a repetir algo que va a fallar
 * igual.
 */
const MOTIVOS_RECHAZO = {
    // Datos mal escritos: se corrigen aqui mismo.
    cc_rejected_bad_filled_card_number:   { texto: 'Revisa el numero de la tarjeta: no coincide con ninguna tarjeta valida.', reintentable: true },
    cc_rejected_bad_filled_date:          { texto: 'Revisa la fecha de vencimiento de la tarjeta.', reintentable: true },
    cc_rejected_bad_filled_security_code: { texto: 'El codigo de seguridad no es correcto. Revisalo e intenta de nuevo.', reintentable: true },
    cc_rejected_bad_filled_other:         { texto: 'Alguno de los datos de la tarjeta no es correcto. Revisalos e intenta de nuevo.', reintentable: true },
    cc_rejected_invalid_installments:     { texto: 'Tu tarjeta no admite esa cantidad de cuotas. Elige otra opcion.', reintentable: true },

    // El banco decide: no se arregla reintentando igual.
    cc_rejected_insufficient_amount:      { texto: 'Tu tarjeta no tiene cupo suficiente para este pago.', reintentable: false },
    cc_rejected_call_for_authorize:       { texto: 'Tu banco debe autorizar este pago. Llamalos, autorizalo y vuelve a intentar.', reintentable: true },
    cc_rejected_card_disabled:            { texto: 'Tu tarjeta esta inactiva. Llama a tu banco para activarla o usa otra.', reintentable: false },
    cc_rejected_card_error:               { texto: 'Tu banco no pudo procesar el pago. Intenta de nuevo o usa otra tarjeta.', reintentable: true },
    cc_rejected_card_type_not_allowed:    { texto: 'Ese tipo de tarjeta no esta habilitado para este pago. Prueba con otra.', reintentable: false },
    rejected_by_bank:                     { texto: 'Tu banco rechazo el pago. Comunicate con ellos o usa otra tarjeta.', reintentable: false },

    // Riesgo y limites: reintentar empeora las cosas.
    cc_rejected_high_risk:                { texto: 'El pago fue rechazado por seguridad. Prueba con otra tarjeta o escribenos y te ayudamos.', reintentable: false },
    cc_rejected_blacklist:                { texto: 'No pudimos procesar el pago con esa tarjeta. Prueba con otra o escribenos.', reintentable: false },
    cc_rejected_max_attempts:             { texto: 'Se alcanzo el limite de intentos con esta tarjeta. Espera un momento o usa otra.', reintentable: false },
    cc_amount_rate_limit_exceeded:        { texto: 'El monto supera el limite permitido para esta tarjeta.', reintentable: false },
    rejected_insufficient_data:           { texto: 'Faltan datos del titular para completar el pago. Revisalos e intenta de nuevo.', reintentable: true },

    // Caso feliz disfrazado: ya pagaste.
    cc_rejected_duplicated_payment:       { texto: 'Ya hay un pago igual en proceso. Revisa tu plan antes de volver a pagar.', reintentable: false },

    // El problema no es del cliente ni de su tarjeta.
    internal_error:                       { texto: 'Hubo un problema al procesar el pago. Intenta de nuevo en unos minutos.', reintentable: true },
    cc_rejected_other_reason:             { texto: 'Tu banco rechazo el pago sin dar un motivo. Intenta de nuevo o usa otra tarjeta.', reintentable: true },
};

/** Estados pendientes, que tampoco significan lo mismo entre si. */
const MOTIVOS_PENDIENTE = {
    pending_contingency:       'Estamos procesando tu pago. Te avisamos por correo en cuanto se confirme, normalmente en unos minutos.',
    pending_review_manual:     'Tu pago esta en revision. Te avisamos por correo apenas se resuelva, en menos de dos dias habiles.',
    pending_waiting_transfer:  'Falta completar la transferencia desde tu banco. Tu plan se activa en cuanto se acredite.',
    pending_waiting_payment:   'Falta completar el pago. Tu plan se activa en cuanto se acredite.',
};

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
        // El motivo exacto lo manda MercadoPago en `status_detail` y el backend ya lo
        // reenvia; antes se ignoraba y todos los rechazos decian lo mismo.
        rejectionReason() {
            return MOTIVOS_RECHAZO[this.paymentResult?.status_detail] || null;
        },

        resultMessage() {
            if (!this.paymentResult) return '';

            const { status, status_detail: detail } = this.paymentResult;

            if (status === 'approved') {
                return 'Tu plan ha sido activado exitosamente. Ya puedes disfrutar de todas las funcionalidades.';
            }

            if (status === 'pending') {
                return MOTIVOS_PENDIENTE[detail]
                    || 'Tu pago esta siendo procesado. Te avisamos por correo cuando se confirme.';
            }

            if (status === 'declined') {
                return this.rejectionReason?.texto
                    || 'Tu pago no pudo ser procesado. Puedes intentar nuevamente o usar otra tarjeta.';
            }

            return 'No pudimos determinar el estado de tu pago. Escribenos y lo revisamos contigo.';
        },

        // Con un rechazo que no se arregla repitiendo (sin cupo, tarjeta inactiva, limite
        // de intentos) el boton de reintentar solo genera otro rechazo: mejor ofrecer
        // cambiar de tarjeta.
        retryLabel() {
            return this.rejectionReason && !this.rejectionReason.reintentable
                ? 'Probar con otra tarjeta'
                : 'Intentar de nuevo';
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

                // El banner se monta una sola vez fuera del router-view: sin esto seguia
                // diciendo "tu periodo ha expirado" encima del "pago aprobado".
                if (data.status === 'approved') {
                    await useSubscription().refresh();
                }
            } catch (err) {
                const respuesta = err.response?.data;

                this.error = respuesta?.message || 'Error al procesar el pago.';

                // La oferta vencio mientras llenaba el formulario. Recargar el plan es lo
                // unico que desatasca: el Brick se creo con el importe viejo y reintentar
                // sin esto vuelve a fallar con el mismo 422, en bucle.
                if (respuesta?.price_changed) {
                    await this.reloadPlanAfterPriceChange();
                }
            } finally {
                this.processing = false;
            }
        },

        /**
         * Vuelve a montar el Brick con el precio nuevo, porque el importe se fija al
         * crearlo y no se puede cambiar en caliente.
         */
        async reloadPlanAfterPriceChange() {
            if (this.brickController) {
                this.brickController.unmount();
                this.brickController = null;
            }

            this.loadingBrick = true;
            await this.loadPlan();
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

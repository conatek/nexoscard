<template>
    <div>
        <!-- Loading (edit mode) -->
        <div v-if="loadingPlan" class="loading-state">
            <div class="spinner-border text-primary"></div>
            <p class="text-muted mt-3">Cargando plan...</p>
        </div>

        <template v-else>
            <!-- Header -->
            <div class="app-page-title">
                <div class="page-title-wrapper">
                    <div class="page-title-heading">
                        <div class="page-title-icon">
                            <i class="fa fa-layer-group icon-gradient bg-mean-fruit"></i>
                        </div>
                        <div>
                            {{ isEdit ? 'Editar Plan' : 'Nuevo Plan' }}
                            <div class="page-title-subheading text-muted" v-if="isEdit">
                                {{ form.display_name }}
                            </div>
                        </div>
                    </div>
                    <div class="page-title-actions">
                        <router-link :to="{ name: 'admin.plans' }" class="btn-action btn-back">
                            <i class="fa fa-arrow-left me-1"></i> Volver
                        </router-link>
                    </div>
                </div>
            </div>

            <!-- Error -->
            <div v-if="generalError" class="error-alert">
                <i class="fa fa-exclamation-circle"></i>
                {{ generalError }}
            </div>

            <form @submit.prevent="submit">
                <div class="form-grid">
                    <!-- Col izquierda: Info general -->
                    <div class="form-section">
                        <div class="section-card">
                            <div class="section-header">
                                <i class="fa fa-info-circle section-icon"></i>
                                <span>Informacion general</span>
                            </div>
                            <div class="section-body">
                                <div class="form-group">
                                    <label class="form-label">Nombre interno <span class="required">*</span></label>
                                    <input v-model="form.name" type="text" class="form-input"
                                           :class="{ 'has-error': errors.name }"
                                           placeholder="ej: basico, pro, enterprise" />
                                    <span v-if="errors.name" class="error-text">{{ errors.name[0] }}</span>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Nombre visible <span class="required">*</span></label>
                                    <input v-model="form.display_name" type="text" class="form-input"
                                           :class="{ 'has-error': errors.display_name }"
                                           placeholder="ej: Básico, Pro, Enterprise" />
                                    <span v-if="errors.display_name" class="error-text">{{ errors.display_name[0] }}</span>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label">Orden</label>
                                        <input v-model.number="form.sort_order" type="number" class="form-input" min="0" />
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Estado</label>
                                        <div class="toggle-wrapper">
                                            <label class="toggle-label">
                                                <input type="checkbox" v-model="form.is_active" class="toggle-input" />
                                                <span class="toggle-switch-inline"></span>
                                                <span>{{ form.is_active ? 'Activo' : 'Inactivo' }}</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Marca de agua NexosCard</label>
                                    <div class="toggle-wrapper">
                                        <label class="toggle-label">
                                            <input type="checkbox" v-model="form.show_watermark" class="toggle-input" />
                                            <span class="toggle-switch-inline"></span>
                                            <span>{{ form.show_watermark ? 'Visible' : 'Oculta' }}</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Plan por defecto</label>
                                    <div class="toggle-wrapper">
                                        <label class="toggle-label">
                                            <input type="checkbox" v-model="form.is_default" class="toggle-input" />
                                            <span class="toggle-switch-inline"></span>
                                            <span>{{ form.is_default ? 'Si' : 'No' }}</span>
                                        </label>
                                    </div>
                                    <span class="field-hint">
                                        Es el plan sobre el que corre la prueba de los nuevos registros.
                                        Solo puede haber uno.
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Plantillas incluidas -->
                        <div class="section-card">
                            <div class="section-header">
                                <i class="fa fa-palette section-icon"></i>
                                <span>Plantillas incluidas</span>
                            </div>
                            <div class="section-body">
                                <label class="check-label mb-2">
                                    <input type="checkbox" v-model="allTemplates" @change="onAllTemplates" />
                                    <span>Todas las plantillas</span>
                                </label>

                                <div v-if="!allTemplates" class="template-list">
                                    <label v-for="(tpl, key) in templates" :key="key" class="check-label">
                                        <input type="checkbox" :value="key"
                                               :checked="isTemplateSelected(key)"
                                               @change="toggleTemplate(key)" />
                                        <span>{{ tpl.name }}</span>
                                    </label>
                                </div>

                                <span v-if="!allTemplates && !selectedTemplates.length" class="error-text">
                                    Selecciona al menos una plantilla.
                                </span>
                            </div>
                        </div>

                        <!-- Bullets comerciales -->
                        <div class="section-card">
                            <div class="section-header">
                                <i class="fa fa-list-check section-icon"></i>
                                <span>Que incluye (bullets)</span>
                            </div>
                            <div class="section-body">
                                <p class="field-hint mb-2">
                                    Se muestran como lista en la pagina de planes.
                                </p>

                                <div v-for="(item, i) in form.features" :key="i" class="feature-row">
                                    <input v-model="form.features[i]" type="text" class="form-input"
                                           placeholder="ej: Codigo QR personalizado" />
                                    <button type="button" class="btn-remove-feature" @click="removeFeature(i)">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>

                                <button type="button" class="btn-add-feature" @click="addFeature">
                                    <i class="fa fa-plus me-1"></i> Agregar linea
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Col derecha: Precios y limites -->
                    <div class="form-section">
                        <div class="section-card">
                            <div class="section-header">
                                <i class="fa fa-dollar-sign section-icon"></i>
                                <span>Precios y limites</span>
                            </div>
                            <div class="section-body">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label">Precio normal (COP) <span class="required">*</span></label>
                                        <input v-model.number="form.price_regular" type="number" class="form-input"
                                               :class="{ 'has-error': errors.price_regular }"
                                               min="0" step="100" />
                                        <span v-if="errors.price_regular" class="error-text">{{ errors.price_regular[0] }}</span>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Ciclo de cobro <span class="required">*</span></label>
                                        <select v-model="form.billing_period" class="form-input">
                                            <option value="yearly">Anual</option>
                                            <option value="monthly">Mensual</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label">Precio de oferta (COP)</label>
                                        <input v-model.number="form.offer_price" type="number" class="form-input"
                                               :class="{ 'has-error': errors.offer_price }"
                                               min="0" step="100" placeholder="Vacio = sin oferta" />
                                        <span v-if="errors.offer_price" class="error-text">{{ errors.offer_price[0] }}</span>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">La oferta termina el</label>
                                        <input v-model="form.offer_ends_at" type="datetime-local" class="form-input"
                                               :class="{ 'has-error': errors.offer_ends_at }" />
                                        <span v-if="errors.offer_ends_at" class="error-text">{{ errors.offer_ends_at[0] }}</span>
                                    </div>
                                </div>

                                <!-- Resumen de lo que se va a cobrar de verdad -->
                                <div class="offer-preview" :class="{ 'is-off': !offerPreview.active }">
                                    <i class="fa" :class="offerPreview.active ? 'fa-bolt' : 'fa-info-circle'"></i>
                                    <span v-if="offerPreview.active">
                                        Se cobrara <strong>${{ offerPreview.price }}</strong>
                                        ({{ offerPreview.discount }}% de descuento)
                                        <template v-if="form.offer_ends_at"> hasta el {{ offerPreview.until }}</template>
                                        <template v-else>, sin fecha de fin</template>
                                    </span>
                                    <span v-else>
                                        Sin oferta vigente: se cobrara <strong>${{ offerPreview.price }}</strong>
                                    </span>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Max. tarjetas <span class="required">*</span></label>
                                    <input v-model.number="form.max_cards" type="number" class="form-input"
                                           :class="{ 'has-error': errors.max_cards }"
                                           min="1" />
                                    <span v-if="errors.max_cards" class="error-text">{{ errors.max_cards[0] }}</span>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Max. productos</label>
                                    <div class="limit-input">
                                        <input v-model.number="form.max_products" type="number" class="form-input"
                                               :disabled="unlimitedProducts" min="1"
                                               :class="{ 'input-disabled': unlimitedProducts }" />
                                        <label class="check-label">
                                            <input type="checkbox" v-model="unlimitedProducts" @change="onUnlimitedProducts" />
                                            <span>Ilimitado</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Max. servicios</label>
                                    <div class="limit-input">
                                        <input v-model.number="form.max_services" type="number" class="form-input"
                                               :disabled="unlimitedServices" min="1"
                                               :class="{ 'input-disabled': unlimitedServices }" />
                                        <label class="check-label">
                                            <input type="checkbox" v-model="unlimitedServices" @change="onUnlimitedServices" />
                                            <span>Ilimitado</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="form-actions">
                            <router-link :to="{ name: 'admin.plans' }" class="btn-cancel">Cancelar</router-link>
                            <button type="submit" class="btn-submit" :disabled="saving">
                                <span v-if="saving" class="spinner"></span>
                                <i v-else class="fa fa-check me-1"></i>
                                {{ saving ? 'Guardando...' : (isEdit ? 'Guardar cambios' : 'Crear plan') }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </template>
    </div>
</template>

<script>
import adminService from '@/services/adminService.js';

export default {
    name: 'AdminPlanForm',

    data() {
        return {
            form: this.emptyForm(),
            unlimitedProducts: false,
            unlimitedServices: false,
            // `available_templates = null` significa "todas". Se maneja con estos dos
            // campos y se convierte al enviar.
            allTemplates: true,
            selectedTemplates: [],
            templates: {},
            errors: {},
            generalError: null,
            saving: false,
            loadingPlan: false,
        };
    },

    computed: {
        isEdit() {
            return !!this.$route.params.id;
        },

        // Refleja exactamente la regla del backend (Plan::isOfferActive/effectivePrice)
        // para que el Master vea qué se va a cobrar antes de guardar.
        offerPreview() {
            const regular = Number(this.form.price_regular) || 0;
            const offer = this.form.offer_price;
            const hasOffer = offer !== null && offer !== '' && Number.isFinite(Number(offer));

            const endsAt = this.form.offer_ends_at ? new Date(this.form.offer_ends_at) : null;
            const notExpired = !endsAt || endsAt > new Date();
            const active = hasOffer && notExpired;

            const price = active ? Number(offer) : regular;
            const discount = active && regular > 0
                ? Math.round((1 - price / regular) * 100)
                : 0;

            return {
                active,
                discount,
                price: price.toLocaleString('es-CO', { maximumFractionDigits: 0 }),
                until: endsAt ? endsAt.toLocaleString('es-CO') : '',
            };
        },
    },

    async created() {
        await this.loadTemplates();

        if (this.isEdit) {
            await this.loadPlan();
        }
    },

    watch: {
        '$route.params.id': {
            async handler(newId) {
                if (newId) {
                    await this.loadPlan();
                } else {
                    this.resetForm();
                }
            },
        },
    },

    methods: {
        emptyForm() {
            return {
                name: '',
                display_name: '',
                price_regular: 0,
                offer_price: null,
                offer_ends_at: '',
                billing_period: 'yearly',
                max_cards: 1,
                max_products: null,
                max_services: null,
                available_templates: null,
                show_watermark: false,
                features: [],
                is_active: true,
                is_default: false,
                sort_order: 0,
            };
        },

        async loadTemplates() {
            try {
                const { data } = await adminService.getTemplates();
                this.templates = data.templates || {};
            } catch {
                this.templates = {};
            }
        },

        async loadPlan() {
            this.loadingPlan = true;
            try {
                const { data } = await adminService.getPlans();
                const planId = parseInt(this.$route.params.id);
                const plan = data.find(p => p.id === planId);

                if (!plan) {
                    this.generalError = 'Plan no encontrado.';
                    return;
                }

                this.form = {
                    name: plan.name,
                    display_name: plan.display_name,
                    price_regular: Number(plan.price_regular),
                    offer_price: plan.offer_price === null ? null : Number(plan.offer_price),
                    offer_ends_at: this.toLocalInput(plan.offer_ends_at),
                    billing_period: plan.billing_period || 'yearly',
                    max_cards: plan.max_cards,
                    max_products: plan.max_products,
                    max_services: plan.max_services,
                    available_templates: plan.available_templates,
                    show_watermark: plan.show_watermark,
                    features: Array.isArray(plan.features) ? [...plan.features] : [],
                    is_active: plan.is_active,
                    is_default: plan.is_default,
                    sort_order: plan.sort_order,
                };

                this.unlimitedProducts = plan.max_products === null;
                this.unlimitedServices = plan.max_services === null;
                this.allTemplates = plan.available_templates === null;
                this.selectedTemplates = plan.available_templates || [];
            } catch {
                this.generalError = 'Error al cargar el plan.';
            } finally {
                this.loadingPlan = false;
            }
        },

        resetForm() {
            this.form = this.emptyForm();
            this.unlimitedProducts = true;
            this.unlimitedServices = true;
            this.allTemplates = true;
            this.selectedTemplates = [];
            this.errors = {};
            this.generalError = null;
        },

        /** ISO del backend -> valor que entiende <input type="datetime-local"> */
        toLocalInput(iso) {
            if (!iso) return '';
            const d = new Date(iso);
            if (Number.isNaN(d.getTime())) return '';
            const pad = n => String(n).padStart(2, '0');
            return `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}`
                + `T${pad(d.getHours())}:${pad(d.getMinutes())}`;
        },

        onAllTemplates() {
            if (this.allTemplates) {
                this.selectedTemplates = [];
            }
        },

        isTemplateSelected(key) {
            return this.selectedTemplates.includes(key);
        },

        toggleTemplate(key) {
            const i = this.selectedTemplates.indexOf(key);
            if (i === -1) {
                this.selectedTemplates.push(key);
            } else {
                this.selectedTemplates.splice(i, 1);
            }
        },

        addFeature() {
            this.form.features.push('');
        },

        removeFeature(i) {
            this.form.features.splice(i, 1);
        },

        onUnlimitedProducts() {
            if (this.unlimitedProducts) {
                this.form.max_products = null;
            } else {
                this.form.max_products = 3;
            }
        },

        onUnlimitedServices() {
            if (this.unlimitedServices) {
                this.form.max_services = null;
            } else {
                this.form.max_services = 3;
            }
        },

        /**
         * Arma el payload real. Antes se enviaba `this.form` tal cual, y como
         * `available_templates` estaba en el form sin ningun input que lo modificara,
         * cada guardado lo mandaba en null y borraba la whitelist del plan.
         */
        buildPayload() {
            return {
                ...this.form,
                available_templates: this.allTemplates ? null : this.selectedTemplates,
                // Se descartan las lineas vacias del repeater.
                features: (this.form.features || [])
                    .map(f => (f || '').trim())
                    .filter(Boolean),
                offer_price: this.form.offer_price === '' ? null : this.form.offer_price,
                offer_ends_at: this.form.offer_ends_at || null,
            };
        },

        async submit() {
            if (!this.allTemplates && !this.selectedTemplates.length) {
                this.generalError = 'Selecciona al menos una plantilla o marca "Todas".';
                return;
            }

            this.saving = true;
            this.errors = {};
            this.generalError = null;

            try {
                const payload = this.buildPayload();

                if (this.isEdit) {
                    await adminService.updatePlan(this.$route.params.id, payload);
                } else {
                    await adminService.storePlan(payload);
                }
                this.$router.push({ name: 'admin.plans' });
            } catch (err) {
                if (err.response?.status === 422) {
                    this.errors = err.response.data.errors || {};
                } else {
                    this.generalError = err.response?.data?.message || 'Error al guardar el plan.';
                }
            } finally {
                this.saving = false;
            }
        },
    },
};
</script>

<style scoped>
.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
    align-items: start;
}

.section-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    border: 1px solid #e2e8f0;
    margin-bottom: 1.5rem;
}

.section-header {
    padding: 1rem 1.25rem;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-weight: 600;
    color: #1e293b;
    border-radius: 12px 12px 0 0;
}

.section-icon {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    background: #f3e8ff;
    color: #7c3aed;
}

.section-body { padding: 1.5rem; }

.form-group { margin-bottom: 1.25rem; }
.form-group:last-child { margin-bottom: 0; }

.form-label {
    display: block;
    font-size: 0.875rem;
    font-weight: 500;
    color: #374151;
    margin-bottom: 0.4rem;
}

.required { color: #ef4444; }

.form-input {
    width: 100%;
    padding: 0.625rem 0.875rem;
    font-size: 0.95rem;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    background: white;
    transition: all 0.2s;
}

.form-input:focus {
    outline: none;
    border-color: #7c3aed;
    box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
}

.form-input.has-error { border-color: #ef4444; }
.form-input.input-disabled { background: #f1f5f9; color: #94a3b8; }

.error-text {
    font-size: 0.8rem;
    color: #ef4444;
    margin-top: 0.25rem;
    display: block;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

/* Toggle */
.toggle-wrapper { margin-top: 0.25rem; }

.toggle-label {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
    font-size: 0.9rem;
    color: #475569;
}

.toggle-input { display: none; }

.toggle-switch-inline {
    width: 40px;
    height: 22px;
    background: #cbd5e1;
    border-radius: 11px;
    position: relative;
    transition: background 0.3s;
}

.toggle-switch-inline::after {
    content: '';
    width: 16px;
    height: 16px;
    background: white;
    border-radius: 50%;
    position: absolute;
    top: 3px;
    left: 3px;
    transition: transform 0.3s;
    box-shadow: 0 1px 2px rgba(0,0,0,0.2);
}

.toggle-input:checked + .toggle-switch-inline {
    background: linear-gradient(135deg, #8b5cf6, #7c3aed);
}

.toggle-input:checked + .toggle-switch-inline::after {
    transform: translateX(18px);
}

/* Limit input with checkbox */
.limit-input {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.limit-input .form-input { flex: 1; }

.check-label {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    font-size: 0.85rem;
    color: #475569;
    white-space: nowrap;
    cursor: pointer;
}

.check-label input[type="checkbox"] {
    accent-color: #7c3aed;
}

/* Actions */
.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 0.75rem;
}

.btn-cancel {
    padding: 0.625rem 1.25rem;
    background: #f1f5f9;
    color: #475569;
    border-radius: 8px;
    text-decoration: none;
    font-size: 0.95rem;
    font-weight: 500;
    transition: all 0.2s;
    border: 1px solid #e2e8f0;
}

.btn-cancel:hover { background: #e2e8f0; color: #334155; }

.btn-submit {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.625rem 1.5rem;
    background: linear-gradient(135deg, #7c3aed, #6d28d9);
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 0.95rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    box-shadow: 0 2px 8px rgba(124, 58, 237, 0.25);
}

.btn-submit:hover:not(:disabled) {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(124, 58, 237, 0.35);
}

.btn-submit:disabled { opacity: 0.7; cursor: not-allowed; }

.spinner {
    width: 16px; height: 16px;
    border: 2px solid rgba(255,255,255,0.3);
    border-top-color: white;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}

@keyframes spin { to { transform: rotate(360deg); } }

/* Error alert */
.error-alert {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem;
    background: #fef2f2;
    border: 1px solid #fecaca;
    border-radius: 8px;
    color: #dc2626;
    font-size: 0.9rem;
    margin-bottom: 1.5rem;
}

/* Back button */
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

.btn-action.btn-back:hover { background: #e2e8f0; }

.loading-state { text-align: center; padding: 4rem 0; }

/* ===== Ayuda bajo un campo ===== */
.field-hint {
    display: block;
    font-size: 0.8rem;
    color: #64748b;
    margin-top: 0.35rem;
    line-height: 1.4;
}

/* ===== Vista previa de la oferta ===== */
.offer-preview {
    display: flex;
    align-items: flex-start;
    gap: 0.5rem;
    margin-top: 0.5rem;
    padding: 0.7rem 0.9rem;
    border-radius: 8px;
    font-size: 0.85rem;
    background: #d1fae5;
    color: #065f46;
    border: 1px solid #a7f3d0;
}

.offer-preview.is-off {
    background: #f1f5f9;
    color: #475569;
    border-color: #e2e8f0;
}

.offer-preview i {
    margin-top: 0.15rem;
}

/* ===== Plantillas incluidas ===== */
.template-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 0.5rem;
    margin-top: 0.5rem;
}

.mb-2 { margin-bottom: 0.5rem; }

/* ===== Repeater de bullets ===== */
.feature-row {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
}

.feature-row .form-input { flex: 1; }

.btn-remove-feature {
    flex-shrink: 0;
    width: 36px;
    height: 36px;
    border: 1px solid #fecaca;
    background: #fef2f2;
    color: #dc2626;
    border-radius: 8px;
    cursor: pointer;
    transition: background 0.2s ease;
}

.btn-remove-feature:hover { background: #fee2e2; }

.btn-add-feature {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 0.9rem;
    border: 1px dashed #cbd5e1;
    background: #f8fafc;
    color: #475569;
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-add-feature:hover {
    border-color: #7c3aed;
    color: #7c3aed;
    background: #f5f3ff;
}

@media (max-width: 768px) {
    .form-grid { grid-template-columns: 1fr; }
    .form-row { grid-template-columns: 1fr; }
}
</style>

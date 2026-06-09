<template>
    <div>
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="fa fa-cogs icon-gradient bg-mean-fruit"></i>
                    </div>
                    <div>
                        Configuracion
                        <div class="page-title-subheading text-muted">
                            Parametros globales de la aplicacion
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="loading" class="loading-state">
            <div class="spinner-border text-primary"></div>
        </div>

        <div v-else class="settings-container">
            <div class="section-card" v-for="setting in settings" :key="setting.key">
                <div class="section-body">
                    <div class="setting-row">
                        <div class="setting-info">
                            <label class="setting-label">{{ setting.description || setting.key }}</label>
                            <span class="setting-key">{{ setting.key }}</span>
                        </div>
                        <div class="setting-input">
                            <input
                                v-model="setting.value"
                                :type="setting.type === 'integer' ? 'number' : 'text'"
                                class="form-input"
                            />
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="error" class="error-alert">
                <i class="fa fa-exclamation-circle"></i>
                {{ error }}
            </div>

            <div v-if="success" class="success-alert">
                <i class="fa fa-check-circle"></i>
                {{ success }}
            </div>

            <button class="btn-save" :disabled="saving" @click="save">
                <span v-if="saving" class="spinner"></span>
                <i v-else class="fa fa-save me-2"></i>
                {{ saving ? 'Guardando...' : 'Guardar cambios' }}
            </button>
        </div>
    </div>
</template>

<script>
import adminService from '@/services/adminService.js';

export default {
    name: 'AdminSettings',

    data() {
        return {
            settings: [],
            loading: true,
            saving: false,
            error: null,
            success: null,
        };
    },

    async created() {
        await this.load();
    },

    methods: {
        async load() {
            this.loading = true;
            try {
                const { data } = await adminService.getSettings();
                this.settings = data;
            } finally {
                this.loading = false;
            }
        },

        async save() {
            this.saving = true;
            this.error = null;
            this.success = null;
            try {
                const payload = this.settings.map(s => ({ key: s.key, value: String(s.value) }));
                await adminService.updateSettings(payload);
                this.success = 'Configuracion actualizada correctamente.';
                setTimeout(() => { this.success = null; }, 3000);
            } catch (err) {
                this.error = err.response?.data?.message || 'Error al guardar.';
            } finally {
                this.saving = false;
            }
        },
    },
};
</script>

<style scoped>
.settings-container {
    max-width: 600px;
}

.section-card {
    background: white;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    margin-bottom: 0.75rem;
}

.section-body {
    padding: 1.25rem;
}

.setting-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1.5rem;
}

.setting-info {
    flex: 1;
}

.setting-label {
    display: block;
    font-weight: 600;
    color: #1e293b;
    font-size: 0.95rem;
    margin-bottom: 0.15rem;
}

.setting-key {
    font-size: 0.75rem;
    color: #94a3b8;
    font-family: monospace;
}

.setting-input {
    width: 160px;
    flex-shrink: 0;
}

.form-input {
    width: 100%;
    padding: 0.5rem 0.75rem;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 0.9rem;
    text-align: right;
}

.form-input:focus {
    outline: none;
    border-color: #7c3aed;
    box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
}

.btn-save {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0.75rem 1.5rem;
    background: linear-gradient(135deg, #8b5cf6, #ec4899);
    color: white;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.95rem;
    cursor: pointer;
    transition: all 0.2s;
    box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
    margin-top: 1rem;
}

.btn-save:hover:not(:disabled) {
    transform: translateY(-1px);
    box-shadow: 0 6px 16px rgba(139, 92, 246, 0.4);
}

.btn-save:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

.error-alert {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1rem;
    background: #fef2f2;
    border: 1px solid #fecaca;
    border-radius: 8px;
    color: #dc2626;
    font-size: 0.9rem;
    margin-top: 0.75rem;
}

.success-alert {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1rem;
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    border-radius: 8px;
    color: #16a34a;
    font-size: 0.9rem;
    margin-top: 0.75rem;
}

.spinner {
    width: 16px;
    height: 16px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-top-color: white;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
    margin-right: 0.5rem;
}

@keyframes spin { to { transform: rotate(360deg); } }

.loading-state {
    text-align: center;
    padding: 4rem 0;
}

@media (max-width: 768px) {
    .setting-row {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    .setting-input {
        width: 100%;
    }
    .form-input {
        text-align: left;
    }
}
</style>

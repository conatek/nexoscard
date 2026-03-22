<template>
    <div class="template-editor">
        <!-- Header -->
        <div class="editor-header d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-1">Editor de Plantilla</h4>
                <p class="text-muted mb-0">{{ company?.name }}</p>
            </div>
            <div class="d-flex gap-2">
                <button
                    class="btn btn-outline-secondary"
                    @click="resetToDefaults"
                    :disabled="saving"
                >
                    Restablecer
                </button>
                <button
                    class="btn btn-primary"
                    @click="saveSettings"
                    :disabled="saving || !hasChanges"
                >
                    <span v-if="saving" class="spinner-border spinner-border-sm me-1"></span>
                    {{ saving ? 'Guardando...' : 'Guardar Cambios' }}
                </button>
            </div>
        </div>

        <div class="editor-container" v-if="!loading">
            <!-- Panel de Controles (Izquierda) -->
            <div class="controls-panel">
                <!-- Selector de Plantilla -->
                <div class="template-selector mb-4">
                    <label class="form-label fw-bold">Plantilla Base</label>
                    <select
                        class="form-select"
                        v-model="selectedTemplate"
                        @change="onTemplateChange"
                    >
                        <option
                            v-for="(tmpl, key) in templates"
                            :key="key"
                            :value="key"
                        >
                            {{ tmpl.name }}
                        </option>
                    </select>
                    <small class="text-muted" v-if="templates[selectedTemplate]">
                        {{ templates[selectedTemplate].description }}
                    </small>
                </div>

                <!-- Acordeón de Secciones -->
                <div class="accordion" id="settingsAccordion">
                    <div
                        class="accordion-item"
                        v-for="(section, sectionKey) in schema"
                        :key="sectionKey"
                    >
                        <h2 class="accordion-header">
                            <button
                                class="accordion-button"
                                :class="{ collapsed: activeSection !== sectionKey }"
                                type="button"
                                @click="toggleSection(sectionKey)"
                            >
                                {{ section._label || sectionKey }}
                            </button>
                        </h2>
                        <div
                            class="accordion-collapse collapse"
                            :class="{ show: activeSection === sectionKey }"
                        >
                            <div class="accordion-body">
                                <div
                                    class="control-group mb-3"
                                    v-for="(field, fieldKey) in getFieldsForSection(section)"
                                    :key="fieldKey"
                                >
                                    <!-- Color Input -->
                                    <template v-if="field.type === 'color'">
                                        <label class="form-label">{{ field.label }}</label>
                                        <div class="input-group">
                                            <input
                                                type="color"
                                                class="form-control form-control-color"
                                                :value="getValue(sectionKey, fieldKey)"
                                                @input="setValue(sectionKey, fieldKey, $event.target.value)"
                                            >
                                            <input
                                                type="text"
                                                class="form-control"
                                                :value="getValue(sectionKey, fieldKey)"
                                                @input="setValue(sectionKey, fieldKey, $event.target.value)"
                                                placeholder="#000000"
                                            >
                                        </div>
                                    </template>

                                    <!-- Range/Slider Input -->
                                    <template v-else-if="field.type === 'range'">
                                        <label class="form-label d-flex justify-content-between">
                                            <span>{{ field.label }}</span>
                                            <span class="text-muted">
                                                {{ getValue(sectionKey, fieldKey) }}{{ field.unit || '' }}
                                            </span>
                                        </label>
                                        <input
                                            type="range"
                                            class="form-range"
                                            :min="field.min"
                                            :max="field.max"
                                            :step="field.step || 1"
                                            :value="getValue(sectionKey, fieldKey)"
                                            @input="setValue(sectionKey, fieldKey, parseFloat($event.target.value))"
                                        >
                                    </template>

                                    <!-- Select Input -->
                                    <template v-else-if="field.type === 'select'">
                                        <label class="form-label">{{ field.label }}</label>
                                        <select
                                            class="form-select"
                                            :value="getValue(sectionKey, fieldKey)"
                                            @change="setValue(sectionKey, fieldKey, $event.target.value)"
                                        >
                                            <option
                                                v-for="opt in field.options"
                                                :key="opt"
                                                :value="opt"
                                            >
                                                {{ opt }}
                                            </option>
                                        </select>
                                    </template>

                                    <!-- Toggle/Checkbox Input -->
                                    <template v-else-if="field.type === 'toggle'">
                                        <div class="form-check form-switch">
                                            <input
                                                type="checkbox"
                                                class="form-check-input"
                                                :id="`toggle-${sectionKey}-${fieldKey}`"
                                                :checked="getValue(sectionKey, fieldKey)"
                                                @change="setValue(sectionKey, fieldKey, $event.target.checked)"
                                            >
                                            <label
                                                class="form-check-label"
                                                :for="`toggle-${sectionKey}-${fieldKey}`"
                                            >
                                                {{ field.label }}
                                            </label>
                                        </div>
                                    </template>

                                    <!-- Number Input -->
                                    <template v-else-if="field.type === 'number'">
                                        <label class="form-label">{{ field.label }}</label>
                                        <input
                                            type="number"
                                            class="form-control"
                                            :value="getValue(sectionKey, fieldKey)"
                                            @input="setValue(sectionKey, fieldKey, parseFloat($event.target.value))"
                                        >
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel de Preview (Derecha) -->
            <div class="preview-panel">
                <div class="preview-header d-flex justify-content-between align-items-center mb-3">
                    <span class="fw-bold">Vista Previa</span>
                    <div class="btn-group btn-group-sm">
                        <button
                            class="btn"
                            :class="previewMode === 'mobile' ? 'btn-primary' : 'btn-outline-secondary'"
                            @click="previewMode = 'mobile'"
                        >
                            Móvil
                        </button>
                        <button
                            class="btn"
                            :class="previewMode === 'tablet' ? 'btn-primary' : 'btn-outline-secondary'"
                            @click="previewMode = 'tablet'"
                        >
                            Tablet
                        </button>
                    </div>
                </div>

                <div
                    class="preview-frame"
                    :class="previewMode"
                    :style="cssVariables"
                >
                    <LivePreview
                        :customization="customization"
                        :template-name="selectedTemplate"
                        :company="company"
                        :sample-card="sampleCard"
                    />
                </div>
            </div>
        </div>

        <!-- Loading State -->
        <div v-else class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="mt-3">Cargando configuración...</p>
        </div>
    </div>
</template>

<script>
import Swal from 'sweetalert2'
import settingService from '../../services/settingService'
import companyService from '../../services/companyService'
import LivePreview from '../../components/templates/LivePreview.vue'

export default {
    name: 'TemplateEditor',

    components: {
        LivePreview,
    },

    data() {
        return {
            loading: true,
            saving: false,
            company: null,
            templates: {},
            selectedTemplate: 'modern',
            schema: {},
            customization: {},
            originalCustomization: {},
            activeSection: 'general',
            previewMode: 'mobile',
            sampleCard: {
                first_name: 'Juan',
                last_name: 'Pérez',
                job_title: 'Director Comercial',
                email: 'juan@empresa.com',
                mobile_phone: '+52 55 1234 5678',
                whatsapp: '5551234567',
                description: 'Profesional con más de 10 años de experiencia en ventas y desarrollo de negocios.',
                photo_path: null,
            },
        }
    },

    computed: {
        companyId() {
            return this.$route.params.companyId
        },

        hasChanges() {
            return JSON.stringify(this.customization) !== JSON.stringify(this.originalCustomization)
        },

        /**
         * Genera CSS Variables a partir de la customización actual
         * Este es el corazón del Live Preview - cada cambio actualiza las variables
         */
        cssVariables() {
            const vars = {}

            for (const [sectionKey, section] of Object.entries(this.customization)) {
                for (const [fieldKey, value] of Object.entries(section)) {
                    const varName = `--${sectionKey}-${this.camelToKebab(fieldKey)}`

                    // Agregar unidad si está definida en el schema
                    const fieldSchema = this.schema[sectionKey]?.[fieldKey]
                    const numValue = parseFloat(value)

                    if (fieldSchema?.unit && !isNaN(numValue)) {
                        vars[varName] = `${numValue}${fieldSchema.unit}`
                    } else {
                        vars[varName] = value
                    }
                }
            }

            return vars
        },
    },

    async created() {
        await this.loadData()
    },

    methods: {
        async loadData() {
            this.loading = true
            try {
                // Cargar plantillas disponibles, settings y datos de empresa en paralelo
                const [templatesRes, settingsRes, companyRes] = await Promise.all([
                    settingService.getTemplates(),
                    settingService.getSettings(this.companyId),
                    companyService.get(this.companyId),
                ])

                this.templates = templatesRes.data.templates
                this.company = companyRes.data

                // Configurar settings actuales
                const settings = settingsRes.data.settings
                this.selectedTemplate = settings.template_name
                this.schema = settingsRes.data.schema
                this.customization = this.extractValues(settingsRes.data.full_customization)
                this.originalCustomization = JSON.parse(JSON.stringify(this.customization))

            } catch (error) {
                console.error('Error cargando datos:', error)
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al cargar la configuración',
                })
            } finally {
                this.loading = false
            }
        },

        /**
         * Extrae solo los valores del schema (sin metadatos de tipo/label)
         */
        extractValues(fullCustomization) {
            const values = {}

            for (const [sectionKey, section] of Object.entries(fullCustomization)) {
                values[sectionKey] = {}

                for (const [fieldKey, field] of Object.entries(section)) {
                    if (fieldKey.startsWith('_')) continue

                    // Si es un objeto con 'value', extraer solo el valor
                    if (typeof field === 'object' && field !== null && 'value' in field) {
                        values[sectionKey][fieldKey] = field.value
                    } else {
                        values[sectionKey][fieldKey] = field
                    }
                }
            }

            return values
        },

        /**
         * Obtener campos de una sección (excluyendo metadatos)
         */
        getFieldsForSection(section) {
            const fields = {}
            for (const [key, value] of Object.entries(section)) {
                if (!key.startsWith('_') && typeof value === 'object') {
                    fields[key] = value
                }
            }
            return fields
        },

        getValue(sectionKey, fieldKey) {
            return this.customization[sectionKey]?.[fieldKey]
        },

        setValue(sectionKey, fieldKey, value) {
            if (!this.customization[sectionKey]) {
                this.customization[sectionKey] = {}
            }
            this.customization[sectionKey][fieldKey] = value
        },

        toggleSection(sectionKey) {
            this.activeSection = this.activeSection === sectionKey ? null : sectionKey
        },

        async onTemplateChange() {
            const result = await Swal.fire({
                title: 'Cambiar plantilla',
                text: 'Cambiar de plantilla restablecerá toda la personalización. ¿Continuar?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, cambiar',
                cancelButtonText: 'Cancelar',
            })

            if (!result.isConfirmed) {
                this.selectedTemplate = this.originalCustomization.template || 'modern'
                return
            }

            this.loading = true
            try {
                // Obtener schema de la nueva plantilla
                const schemaRes = await settingService.getSchema(this.selectedTemplate)
                this.schema = schemaRes.data.schema

                // Extraer valores por defecto
                this.customization = this.extractValues(schemaRes.data.schema)

            } catch (error) {
                console.error('Error cambiando plantilla:', error)
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al cambiar de plantilla',
                })
            } finally {
                this.loading = false
            }
        },

        async saveSettings() {
            this.saving = true
            try {
                await settingService.updateSettings(this.companyId, {
                    template_name: this.selectedTemplate,
                    customization: this.customization,
                })

                this.originalCustomization = JSON.parse(JSON.stringify(this.customization))
                Swal.fire({
                    icon: 'success',
                    title: 'Guardado',
                    text: 'Configuración guardada correctamente',
                    timer: 2000,
                    showConfirmButton: false,
                })

            } catch (error) {
                console.error('Error guardando:', error)
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al guardar la configuración',
                })
            } finally {
                this.saving = false
            }
        },

        async resetToDefaults() {
            const result = await Swal.fire({
                title: 'Restablecer configuración',
                text: '¿Restablecer toda la configuración a los valores por defecto?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, restablecer',
                cancelButtonText: 'Cancelar',
            })

            if (!result.isConfirmed) {
                return
            }

            this.saving = true
            try {
                const response = await settingService.resetSettings(this.companyId)
                this.customization = this.extractValues(response.data.full_customization)
                this.originalCustomization = JSON.parse(JSON.stringify(this.customization))
                Swal.fire({
                    icon: 'success',
                    title: 'Restablecido',
                    text: 'Configuración restablecida correctamente',
                    timer: 2000,
                    showConfirmButton: false,
                })

            } catch (error) {
                console.error('Error restableciendo:', error)
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al restablecer la configuración',
                })
            } finally {
                this.saving = false
            }
        },

        /**
         * Convierte camelCase a kebab-case para nombres de CSS variables
         */
        camelToKebab(str) {
            return str.replace(/([a-z])([A-Z])/g, '$1-$2').toLowerCase()
        },
    },
}
</script>

<style scoped>
.template-editor {
    padding: 1.5rem;
    max-width: 100%;
    overflow: hidden;
}

.editor-container {
    display: grid;
    grid-template-columns: 380px 1fr;
    gap: 2rem;
    height: calc(100vh - 180px);
}

.controls-panel {
    overflow-y: auto;
    padding-right: 1rem;
}

.preview-panel {
    display: flex;
    flex-direction: column;
    min-height: 0;
}

.preview-frame {
    flex: 1;
    background: #f5f5f5;
    border-radius: 12px;
    overflow-y: auto;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    padding: 1rem;
    min-height: 0;
}

.preview-frame.mobile {
    max-width: 100%;
}

.preview-frame.mobile > :deep(*) {
    width: 375px;
    max-width: 100%;
    height: fit-content;
}

.preview-frame.tablet > :deep(*) {
    width: 768px;
    max-width: 100%;
    height: fit-content;
}

.accordion-button:not(.collapsed) {
    background-color: #f0f0f0;
    color: #333;
}

.form-control-color {
    width: 50px;
    padding: 0.25rem;
}

.control-group {
    padding-bottom: 0.75rem;
    border-bottom: 1px solid #eee;
}

.control-group:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.template-selector select {
    font-weight: 500;
}

@media (max-width: 1200px) {
    .editor-container {
        grid-template-columns: 1fr;
        height: auto;
    }

    .controls-panel {
        max-height: 400px;
    }

    .preview-panel {
        order: -1;
    }
}
</style>

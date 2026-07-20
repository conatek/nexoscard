<template>
    <div class="live-preview" :style="cssVariablesStyle">
        <component
            :is="currentTemplateComponent"
            :customization="customization"
            :company="company"
            :card="sampleCard"
            :services="realServices"
            :products="realProducts"
        />
    </div>
</template>

<script>
import TemplateModern from './TemplateModern.vue'
import TemplateClassic from './TemplateClassic.vue'
import TemplateMinimal from './TemplateMinimal.vue'
import TemplateCreative from './TemplateCreative.vue'
import TemplateCyber from './TemplateCyber.vue'
import TemplateVibrant from './TemplateVibrant.vue'
import TemplateImpulso from './TemplateImpulso.vue'

export default {
    name: 'LivePreview',

    components: {
        TemplateModern,
        TemplateClassic,
        TemplateMinimal,
        TemplateCreative,
        TemplateCyber,
        TemplateVibrant,
        TemplateImpulso,
    },

    props: {
        customization: {
            type: Object,
            required: true,
        },
        templateName: {
            type: String,
            default: 'modern',
        },
        company: {
            type: Object,
            default: () => ({
                name: 'Mi Empresa',
                logo_path: null,
                slug: 'mi-empresa',
            }),
        },
        sampleCard: {
            type: Object,
            default: () => ({}),
        },
        services: {
            type: Array,
            default: () => [],
        },
        products: {
            type: Array,
            default: () => [],
        },
    },


    computed: {
        realServices() {
            return this.services.filter(s => s.is_active !== false)
        },
        realProducts() {
            return this.products.filter(p => p.is_active !== false)
        },
        currentTemplateComponent() {
            const templates = {
                modern: 'TemplateModern',
                classic: 'TemplateClassic',
                minimal: 'TemplateMinimal',
                creative: 'TemplateCreative',
                cyber: 'TemplateCyber',
                vibrant: 'TemplateVibrant',
                impulso: 'TemplateImpulso',
            }
            return templates[this.templateName] || 'TemplateModern'
        },

        /**
         * Convierte el objeto customization a CSS Variables
         * Esta es la magia del Live Preview reactivo
         */
        cssVariablesStyle() {
            const vars = {}

            for (const [sectionKey, section] of Object.entries(this.customization || {})) {
                if (typeof section !== 'object' || section === null) continue

                for (const [fieldKey, value] of Object.entries(section)) {
                    // Crear nombre de variable CSS: --section-field
                    const varName = `--${sectionKey}-${this.camelToKebab(fieldKey)}`
                    vars[varName] = value
                }
            }

            return vars
        },
    },

    methods: {
        camelToKebab(str) {
            return str.replace(/([a-z])([A-Z])/g, '$1-$2').toLowerCase()
        },
    },
}
</script>

<style scoped>
.live-preview {
    width: 100%;
    height: fit-content;
    background: var(--general-color-fondo, #ffffff);
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}
</style>

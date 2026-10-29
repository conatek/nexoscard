<template>
    <!-- Bloque de "Atención y soporte" que va en el pie de todas las páginas de NexosCard.
         No impone colores ni tamaño: hereda los del pie que lo contiene. -->
    <div class="si-support" :class="{ 'si-support--inline': inline }">
        <p class="si-title">Atención y soporte:</p>
        <p class="si-line">
            WhatsApp: <a :href="whatsappUrl" target="_blank" rel="noopener">{{ whatsappLabel }}</a> / Correo: <a :href="`mailto:${contact.email}`">{{ contact.email }}</a>
        </p>
        <p class="si-line">Operación comercial: {{ city }}.</p>
    </div>
</template>

<script>
import { SUPPORT_FALLBACK, SUPPORT_CITY, getSupportContact, formatWhatsapp } from '@/utils/supportContact.js';

export default {
    name: 'SupportInfo',

    props: {
        // En una sola línea, para el pie bajo de la app interna.
        inline: { type: Boolean, default: false },
    },

    data() {
        return {
            contact: { ...SUPPORT_FALLBACK },
            city:    SUPPORT_CITY,
        };
    },

    computed: {
        whatsappLabel() {
            return formatWhatsapp(this.contact.whatsapp);
        },
        whatsappUrl() {
            return `https://wa.me/${this.contact.whatsapp}`;
        },
    },

    async created() {
        this.contact = await getSupportContact();
    },
};
</script>

<style scoped>
.si-support {
    font-size: inherit;
    line-height: 1.6;
}

.si-support p {
    margin: 0;
}

.si-title {
    font-weight: 600;
}

.si-support a {
    color: inherit;
    font-weight: 500;
    word-break: break-word;
}

.si-support a:hover {
    opacity: 0.8;
}

/* En una línea solo cuando cabe; en mobile vuelve a ir apilado. */
@media (min-width: 769px) {
    .si-support--inline {
        display: flex;
        flex-wrap: wrap;
        align-items: baseline;
        column-gap: 0.75em;
    }

    .si-support--inline .si-line + .si-line::before {
        content: '·';
        margin-right: 0.75em;
    }
}
</style>

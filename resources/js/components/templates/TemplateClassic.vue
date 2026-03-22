<template>
    <div class="card-template classic">
        <div class="coming-soon">
            <i class="bi bi-palette"></i>
            <h4>Plantilla Clásica</h4>
            <p>Próximamente disponible</p>
        </div>
    </div>
</template>

<script>
export default {
    name: 'TemplateClassic',

    props: {
        customization: Object,
        company: Object,
        card: Object,
        services: Array,
        products: Array,
    },
}
</script>

<style scoped>
.card-template.classic {
    min-height: 400px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #1a1a2e, #16213e);
    color: white;
}

.coming-soon {
    text-align: center;
    padding: 2rem;
}

.coming-soon i {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.7;
}

.coming-soon h4 {
    margin-bottom: 0.5rem;
}

.coming-soon p {
    opacity: 0.7;
}
</style>

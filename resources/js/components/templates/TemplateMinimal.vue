<template>
    <div class="card-template minimal">
        <div class="coming-soon">
            <i class="bi bi-square"></i>
            <h4>Plantilla Minimalista</h4>
            <p>Próximamente disponible</p>
        </div>
    </div>
</template>

<script>
export default {
    name: 'TemplateMinimal',

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
.card-template.minimal {
    min-height: 400px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #ffffff;
    color: #000000;
}

.coming-soon {
    text-align: center;
    padding: 2rem;
}

.coming-soon i {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.coming-soon h4 {
    margin-bottom: 0.5rem;
    font-weight: 300;
}

.coming-soon p {
    opacity: 0.5;
}
</style>

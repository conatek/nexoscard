<template>
    <div class="card-template creative">
        <div class="ambient-light light-1" v-if="customization?.hero?.mostrarFondoEfecto"></div>
        <div class="ambient-light light-2" v-if="customization?.hero?.mostrarFondoEfecto"></div>

        <main class="glass-container">
            <header class="creative-header">
                <div class="company-badge" v-if="company?.logo_path || company?.name">
                    <img v-if="company?.logo_path" :src="company.logo_path" :alt="company.name" class="mini-logo">
                    <span v-else>{{ company?.name }}</span>
                </div>

                <div class="avatar-wrapper">
                    <img
                        v-if="card?.photo_path"
                        :src="card.photo_path"
                        :alt="`${card.first_name} ${card.last_name}`"
                        class="avatar-image"
                    >
                    <div v-else class="avatar-placeholder">
                        <i class="bi bi-person"></i>
                    </div>
                </div>
            </header>

            <section class="personal-info">
                <h1 class="name">{{ card?.first_name || 'Nombre' }} <span class="fw-light">{{ card?.last_name || 'Apellido' }}</span></h1>
                <p class="role">{{ card?.job_title || 'Cargo profesional' }}</p>
                <p class="bio" v-if="card?.description">{{ card.description }}</p>
            </section>

            <section class="quick-actions">
                <a v-if="card?.mobile_phone" :href="`tel:${card.mobile_phone}`" class="action-btn">
                    <i class="bi bi-telephone-fill"></i>
                </a>
                <a v-if="card?.whatsapp" :href="whatsappLink" class="action-btn whatsapp">
                    <i class="bi bi-whatsapp"></i>
                </a>
                <a v-if="card?.email" :href="`mailto:${card.email}`" class="action-btn">
                    <i class="bi bi-envelope-fill"></i>
                </a>
                <a v-if="company?.web" :href="company.web" target="_blank" class="action-btn">
                    <i class="bi bi-globe"></i>
                </a>
            </section>

            <section class="content-cards" v-if="services?.length || products?.length">
                <div class="glass-card" v-if="services?.length">
                    <h3 class="card-title">Servicios</h3>
                    <div class="pill-list">
                        <span class="glass-pill" v-for="service in services" :key="'s-'+service.id">
                            {{ service.name }}
                        </span>
                    </div>
                </div>

                <div class="glass-card" v-if="products?.length">
                    <h3 class="card-title">Productos Destacados</h3>
                    <div class="pill-list">
                        <span class="glass-pill" v-for="product in products" :key="'p-'+product.id">
                            {{ product.name }}
                        </span>
                    </div>
                </div>
            </section>

            <footer class="social-footer">
                <a v-if="company?.linkedin" :href="company.linkedin" class="social-link"><i class="bi bi-linkedin"></i></a>
                <a v-if="company?.instagram" :href="company.instagram" class="social-link"><i class="bi bi-instagram"></i></a>
                <a v-if="company?.facebook" :href="company.facebook" class="social-link"><i class="bi bi-facebook"></i></a>
            </footer>
        </main>
    </div>
</template>

<script>
export default {
    name: 'TemplateCreative',
    props: {
        customization: { type: Object, default: () => ({}) },
        company: { type: Object, default: () => ({}) },
        card: { type: Object, default: () => ({}) },
        services: { type: Array, default: () => [] },
        products: { type: Array, default: () => [] }
    },
    computed: {
        whatsappLink() {
            if (!this.card?.whatsapp) return '#'
            const phone = this.card.whatsapp.replace(/\D/g, '')
            return `https://api.whatsapp.com/send?phone=${phone}`
        }
    }
}
</script>

<style scoped>
.card-template.creative {
    /* Variables base caen en cascada desde LivePreview, aquí definimos fallbacks */
    --bg-color: var(--general-color-fondo, #0f172a);
    --accent-color: var(--general-color-acento, #38bdf8);
    --font-family: var(--general-fuente-principal, 'Poppins', sans-serif);
    --radius: var(--hero-radio-borde, 24px);
    --glass-opacity: var(--glass-opacidad, 0.1);
    --glass-blur: var(--glass-desenfoque, 12px);

    background-color: var(--bg-color);
    font-family: var(--font-family);
    color: #ffffff; /* Esta plantilla asume un fondo oscuro por defecto */
    min-height: 100vh;
    padding: 2rem 1rem;
    position: relative;
    overflow: hidden;
    display: flex;
    justify-content: center;
    align-items: flex-start;
}

/* Luces de ambiente en el fondo */
.ambient-light {
    position: absolute;
    border-radius: 50%;
    filter: blur(60px);
    z-index: 0;
    opacity: 0.5;
}
.light-1 {
    top: -10%;
    left: -10%;
    width: 300px;
    height: 300px;
    background: var(--accent-color);
}
.light-2 {
    bottom: -10%;
    right: -10%;
    width: 250px;
    height: 250px;
    background: #c084fc; /* Un color secundario fijo para contraste */
}

/* Contenedor Glassmorphism */
.glass-container {
    position: relative;
    z-index: 1;
    width: 100%;
    max-width: 400px;
    background: rgba(255, 255, 255, var(--glass-opacity));
    backdrop-filter: blur(var(--glass-blur));
    -webkit-backdrop-filter: blur(var(--glass-blur));
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--radius);
    padding: 2rem;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
}

.creative-header {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-bottom: 1.5rem;
}

.company-badge {
    background: rgba(0, 0, 0, 0.2);
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-size: 0.8rem;
    letter-spacing: 1px;
    text-transform: uppercase;
    margin-bottom: 1.5rem;
}

.mini-logo {
    height: 20px;
    width: auto;
}

.avatar-wrapper {
    position: relative;
    padding: 5px;
    background: linear-gradient(135deg, var(--accent-color), transparent);
    border-radius: 50%;
}

.avatar-image, .avatar-placeholder {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid var(--bg-color);
}

.avatar-placeholder {
    display: flex;
    justify-content: center;
    align-items: center;
    background: #334155;
    font-size: 3rem;
    color: rgba(255,255,255,0.5);
}

.personal-info {
    text-align: center;
    margin-bottom: 2rem;
}

.name {
    font-size: 1.5rem;
    font-weight: 600;
    margin: 0 0 0.25rem 0;
}

.role {
    color: var(--accent-color);
    font-size: 0.95rem;
    font-weight: 500;
    margin: 0 0 1rem 0;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.bio {
    font-size: 0.9rem;
    line-height: 1.5;
    color: rgba(255, 255, 255, 0.8);
    margin: 0;
}

.quick-actions {
    display: flex;
    justify-content: center;
    gap: 1rem;
    margin-bottom: 2rem;
}

.action-btn {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.1);
    color: #fff;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 1.2rem;
    text-decoration: none;
    transition: all 0.3s ease;
    border: 1px solid rgba(255, 255, 255, 0.05);
}

.action-btn:hover {
    background: var(--accent-color);
    transform: translateY(-3px);
    box-shadow: 0 10px 20px -10px var(--accent-color);
}

.action-btn.whatsapp:hover { background: #25d366; box-shadow: 0 10px 20px -10px #25d366; }

.glass-card {
    background: rgba(0, 0, 0, 0.2);
    border-radius: calc(var(--radius) / 1.5);
    padding: 1.25rem;
    margin-bottom: 1rem;
    border: 1px solid rgba(255, 255, 255, 0.05);
}

.card-title {
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: rgba(255, 255, 255, 0.6);
    margin: 0 0 1rem 0;
}

.pill-list {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.glass-pill {
    background: rgba(255, 255, 255, 0.05);
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    font-size: 0.85rem;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.social-footer {
    display: flex;
    justify-content: center;
    gap: 1.5rem;
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.social-link {
    color: rgba(255, 255, 255, 0.5);
    font-size: 1.2rem;
    transition: color 0.2s;
}

.social-link:hover {
    color: var(--accent-color);
}
</style>

<template>
    <div class="legal-page">
        <header class="legal-header">
            <div class="legal-header-inner">
                <router-link to="/landing" class="legal-brand" aria-label="NexosCard">
                    <img :src="logoUrl" alt="Nexos Card" class="legal-logo">
                </router-link>
                <router-link to="/login" class="legal-login">Iniciar sesión</router-link>
            </div>
        </header>

        <main class="legal-main">
            <article class="legal-card">
                <p class="legal-eyebrow">Información legal</p>
                <h1 class="legal-title">{{ title }}</h1>
                <p class="legal-updated">Última actualización: {{ updated }}</p>

                <div class="legal-body">
                    <slot />
                </div>
            </article>
        </main>

        <footer class="legal-footer">
            <nav class="legal-footer-links">
                <router-link to="/politica-privacidad">Política de Privacidad</router-link>
                <span aria-hidden="true">·</span>
                <router-link to="/terminos-condiciones">Términos y Condiciones</router-link>
            </nav>
            <p>{{ year }} Nexos Card · Tarjetas de contacto digitales e interactivas.</p>
        </footer>
    </div>
</template>

<script>
export default {
    name: 'LegalLayout',
    props: {
        title:   { type: String, required: true },
        updated: { type: String, required: true },
    },
    data() {
        return {
            // Enlazado dinámico: con un src literal, Vite intenta importar el archivo de public/.
            logoUrl: '/images/nexos-logo.png',
        };
    },
    computed: {
        year() {
            return new Date().getFullYear();
        },
    },
    mounted() {
        document.title = `${this.title} | Nexos Card`;
        window.scrollTo(0, 0);
    },
};
</script>

<style scoped>
.legal-page {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    background: #f8fafc;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    color: #334155;
}

.legal-header {
    background: #ffffff;
    border-bottom: 1px solid #e2e8f0;
}

.legal-header-inner {
    max-width: 860px;
    margin: 0 auto;
    padding: 0.875rem 1rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.legal-logo {
    height: 36px;
    width: auto;
    display: block;
}

.legal-login {
    color: #7c3aed;
    font-weight: 600;
    font-size: 0.9rem;
    text-decoration: none;
    padding: 0.5rem 1rem;
    border-radius: 10px;
    transition: all 0.2s ease;
}

.legal-login:hover {
    background: #f5f3ff;
}

.legal-main {
    width: 100%;
    max-width: 860px;
    margin: 0 auto;
    padding: 2rem 1rem 3rem;
}

.legal-card {
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    padding: 2.5rem;
}

.legal-eyebrow {
    margin: 0 0 0.5rem;
    font-size: 0.75rem;
    font-weight: 600;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    background: linear-gradient(135deg, #8b5cf6, #ec4899);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
}

.legal-title {
    margin: 0;
    font-size: 2rem;
    font-weight: 800;
    line-height: 1.2;
    color: #1e293b;
}

.legal-updated {
    margin: 0.5rem 0 2rem;
    font-size: 0.85rem;
    color: #64748b;
}

.legal-body {
    font-size: 0.975rem;
    line-height: 1.7;
}

.legal-body :deep(h2) {
    margin: 2rem 0 0.75rem;
    font-size: 1.15rem;
    font-weight: 700;
    color: #1e293b;
}

.legal-body :deep(p) {
    margin: 0 0 1rem;
}

.legal-body :deep(ul) {
    margin: 0 0 1rem;
    padding-left: 1.25rem;
}

.legal-body :deep(li) {
    margin-bottom: 0.35rem;
}

.legal-body :deep(li::marker) {
    color: #8b5cf6;
}

.legal-body :deep(a) {
    color: #7c3aed;
    font-weight: 500;
}

.legal-footer {
    margin-top: auto;
    padding: 1.5rem 1rem;
    text-align: center;
    font-size: 0.85rem;
    color: #64748b;
    border-top: 1px solid #e2e8f0;
    background: #ffffff;
}

.legal-footer p {
    margin: 0.5rem 0 0;
}

.legal-footer-links {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.legal-footer-links a {
    color: #475569;
    text-decoration: none;
    font-weight: 500;
}

.legal-footer-links a:hover,
.legal-footer-links a.router-link-exact-active {
    color: #7c3aed;
}

@media (max-width: 640px) {
    .legal-card {
        padding: 1.5rem 1.25rem;
    }

    .legal-title {
        font-size: 1.6rem;
    }

    .legal-logo {
        height: 30px;
    }
}
</style>

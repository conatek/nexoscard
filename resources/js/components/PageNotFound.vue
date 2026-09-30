<template>
    <div class="nf-page" :class="{ 'nf-motion': motionOk }">
        <div class="nf-aurora" aria-hidden="true">
            <span class="nf-blob nf-blob-1"></span>
            <span class="nf-blob nf-blob-2"></span>
        </div>
        <div class="nf-grid" aria-hidden="true"></div>

        <header class="nf-top">
            <router-link to="/inicio" class="nf-brand" aria-label="Nexos Card">
                <img :src="logoUrl" alt="">
                <span>NEXOS Card</span>
            </router-link>
        </header>

        <main class="nf-main">
            <!-- 404 con el "0" convertido en un enlace roto: la marca es "un solo enlace" -->
            <div class="nf-code" aria-hidden="true">
                <span class="nf-digit">4</span>
                <svg class="nf-link" viewBox="0 0 200 200" fill="none">
                    <defs>
                        <linearGradient id="nf-grad" x1="0" y1="0" x2="200" y2="200" gradientUnits="userSpaceOnUse">
                            <stop offset="0" stop-color="#7c3aed" />
                            <stop offset="0.45" stop-color="#d946ef" />
                            <stop offset="0.75" stop-color="#fb7185" />
                            <stop offset="1" stop-color="#fdc5c0" />
                        </linearGradient>
                        <radialGradient id="nf-core" cx="0.5" cy="0.5" r="0.5">
                            <stop offset="0" stop-color="#d946ef" stop-opacity="0.35" />
                            <stop offset="1" stop-color="#d946ef" stop-opacity="0" />
                        </radialGradient>
                    </defs>

                    <circle cx="100" cy="100" r="96" fill="url(#nf-core)" />
                    <circle class="nf-orbit" cx="100" cy="100" r="88" stroke="url(#nf-grad)" stroke-width="3" stroke-dasharray="10 14" stroke-linecap="round" />

                    <!-- Mitad izquierda del eslabón -->
                    <g class="nf-half nf-half-left">
                        <path d="M88 76 H66 a24 24 0 0 0 0 48 H88" stroke="url(#nf-grad)" stroke-width="13" stroke-linecap="round" />
                        <path d="M74 100 H86" stroke="url(#nf-grad)" stroke-width="13" stroke-linecap="round" />
                    </g>
                    <!-- Mitad derecha del eslabón -->
                    <g class="nf-half nf-half-right">
                        <path d="M112 76 H134 a24 24 0 0 1 0 48 H112" stroke="url(#nf-grad)" stroke-width="13" stroke-linecap="round" />
                        <path d="M114 100 H126" stroke="url(#nf-grad)" stroke-width="13" stroke-linecap="round" />
                    </g>

                    <!-- Chispas en el corte -->
                    <g class="nf-sparks" stroke="#fdc5c0" stroke-width="3.5" stroke-linecap="round">
                        <path d="M100 62 V52" />
                        <path d="M100 148 V138" />
                        <path d="M88 58 L83 50" />
                        <path d="M112 58 L117 50" />
                        <path d="M88 142 L83 150" />
                        <path d="M112 142 L117 150" />
                    </g>
                </svg>
                <span class="nf-digit">4</span>
            </div>

            <p class="nf-url">
                <span class="nf-dot"></span>
                <span class="nf-url-text">nexoscard.com{{ $route.path }}</span>
            </p>

            <h1 class="nf-title">{{ copy.title }}</h1>
            <p class="nf-text">{{ copy.text }}</p>

            <div class="nf-actions">
                <router-link v-if="isAuthenticated" to="/" class="nf-btn nf-btn-primary">
                    <i class="fas fa-th-large" aria-hidden="true"></i> Ir a mi panel
                </router-link>
                <router-link v-else to="/inicio" class="nf-btn nf-btn-primary">
                    <i class="fas fa-home" aria-hidden="true"></i> Ir al inicio
                </router-link>
                <router-link v-if="!isAuthenticated" to="/register" class="nf-btn nf-btn-glass">
                    Crear Mi Tarjeta <i class="fas fa-arrow-right" aria-hidden="true"></i>
                </router-link>
            </div>

            <p class="nf-pitch">
                <i class="fas fa-link" aria-hidden="true"></i>
                <span>Tu presencia profesional y comercial, en un enlace que <strong>sí funciona</strong>.</span>
            </p>
        </main>

        <footer class="nf-footer">
            <span>© {{ year }} Nexos Card</span>
            <router-link to="/politica-privacidad">Política de Privacidad</router-link>
            <router-link to="/terminos-condiciones">Términos y Condiciones</router-link>
        </footer>
    </div>
</template>

<script>
import { useAuth } from '@/stores/auth';
import { loadFonts } from '@/views/public/landing/content.js';

// El texto depende de qué se buscaba: quien llega a una tarjeta o empresa inexistente casi
// siempre viene de un enlace que alguien le compartió, y lo útil es decirle qué hacer.
const COPY = {
    page: {
        title: 'Este enlace no lleva a ninguna parte',
        text: 'La página que buscas no existe o cambió de dirección. Revisa que esté bien escrita o vuelve al inicio.',
    },
    company: {
        title: 'No encontramos esta empresa',
        text: 'La dirección no corresponde a ninguna empresa en Nexos Card. Puede que haya cambiado: pide a quien te la compartió el enlace actualizado.',
    },
    card: {
        title: 'Esta tarjeta no existe',
        text: 'No encontramos ninguna tarjeta digital en esta dirección. Puede que haya cambiado: pide a quien te la compartió el enlace actualizado.',
    },
};

export default {
    name: 'PageNotFound',

    props: {
        kind: {
            type: String,
            default: 'page',
            validator: (v) => Object.keys(COPY).includes(v),
        },
    },

    data() {
        return {
            // Enlazado dinámico: con un src literal, Vite intenta importar el archivo de public/.
            logoUrl: '/images/landing/logo-mark.webp',
            motionOk: !window.matchMedia('(prefers-reduced-motion: reduce)').matches,
        };
    },

    computed: {
        copy() {
            return COPY[this.kind];
        },
        isAuthenticated() {
            return useAuth().isAuthenticated.value;
        },
        year() {
            return new Date().getFullYear();
        },
    },

    mounted() {
        document.title = 'Página no encontrada | Nexos Card';
        loadFonts('lt-landing-fonts', 'https://fonts.googleapis.com/css2?family=Rammetto+One&display=swap');
    },
};
</script>

<style scoped>
.nf-page {
    --nf-brand: linear-gradient(115deg, #7c3aed 0%, #d946ef 42%, #fb7185 72%, #fdc5c0 100%);
    --nf-line: rgba(255, 255, 255, 0.1);
    --nf-muted: #a6a9cc;

    position: relative;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    background: #06071a;
    color: #eef0ff;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    overflow: hidden;
}

/* ---------- Fondo ---------- */
.nf-aurora {
    position: absolute;
    inset: 0;
    pointer-events: none;
}

.nf-blob {
    position: absolute;
    border-radius: 50%;
}

.nf-blob-1 {
    width: 70vmax;
    height: 70vmax;
    top: -35vmax;
    right: -25vmax;
    background: radial-gradient(circle, rgba(217, 70, 239, 0.45), transparent 62%);
}

.nf-blob-2 {
    width: 65vmax;
    height: 65vmax;
    bottom: -35vmax;
    left: -25vmax;
    background: radial-gradient(circle, rgba(124, 58, 237, 0.55), transparent 62%);
}

.nf-motion .nf-blob-1 { animation: nf-drift 18s ease-in-out infinite alternate; }
.nf-motion .nf-blob-2 { animation: nf-drift 22s ease-in-out infinite alternate-reverse; }

.nf-grid {
    position: absolute;
    inset: 0;
    background-image:
        linear-gradient(rgba(255, 255, 255, 0.05) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255, 255, 255, 0.05) 1px, transparent 1px);
    background-size: 64px 64px;
    -webkit-mask-image: radial-gradient(ellipse at 50% 45%, #000 15%, transparent 65%);
    mask-image: radial-gradient(ellipse at 50% 45%, #000 15%, transparent 65%);
    pointer-events: none;
}

/* ---------- Cabecera ---------- */
.nf-top {
    position: relative;
    padding: 1.5rem 1.25rem 0;
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
}

.nf-brand {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: #ffffff;
    text-decoration: none;
}

.nf-brand:hover {
    color: #ffffff;
}

.nf-brand img {
    width: 38px;
    height: 38px;
}

.nf-brand span {
    font-family: 'Rammetto One', 'Outfit', sans-serif;
    font-size: 1.2rem;
    white-space: nowrap;
}

/* ---------- Contenido ---------- */
.nf-main {
    position: relative;
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 2rem 1.25rem 3rem;
    text-align: center;
}

.nf-code {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: clamp(0.25rem, 1.5vw, 1rem);
    line-height: 1;
}

.nf-digit {
    font-family: 'Outfit', 'Inter', sans-serif;
    font-weight: 800;
    font-size: clamp(7rem, 22vw, 13rem);
    letter-spacing: -0.04em;
    background: var(--nf-brand);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    filter: drop-shadow(0 20px 50px rgba(217, 70, 239, 0.35));
}

.nf-link {
    width: clamp(6.5rem, 20vw, 12rem);
    height: auto;
    overflow: visible;
}

.nf-orbit {
    transform-origin: 100px 100px;
}

.nf-motion .nf-orbit {
    animation: nf-spin 16s linear infinite;
}

.nf-motion .nf-half-left {
    animation: nf-pull-left 3.2s ease-in-out infinite;
}

.nf-motion .nf-half-right {
    animation: nf-pull-right 3.2s ease-in-out infinite;
}

.nf-sparks {
    transform-origin: 100px 100px;
}

.nf-motion .nf-sparks {
    animation: nf-spark 3.2s ease-in-out infinite;
}

.nf-url {
    display: inline-flex;
    align-items: center;
    gap: 0.6rem;
    max-width: 100%;
    margin: 1.5rem 0 0;
    padding: 0.45rem 1rem;
    border: 1px solid var(--nf-line);
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.045);
    backdrop-filter: blur(10px);
    font-family: 'Fira Code', 'Roboto Mono', monospace;
    font-size: 0.88rem;
    color: #d6d8f5;
}

.nf-url-text {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    text-decoration: line-through;
    text-decoration-color: rgba(251, 113, 133, 0.7);
}

.nf-dot {
    flex-shrink: 0;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #fb7185;
    box-shadow: 0 0 12px #fb7185;
}

.nf-title {
    max-width: 720px;
    margin: 1.75rem 0 0;
    font-family: 'Outfit', 'Inter', sans-serif;
    font-weight: 800;
    font-size: clamp(1.9rem, 4.4vw, 3.2rem);
    line-height: 1.1;
    letter-spacing: -0.02em;
    color: #ffffff;
}

.nf-text {
    max-width: 560px;
    margin: 1rem 0 0;
    font-size: 1.1rem;
    line-height: 1.6;
    color: var(--nf-muted);
}

.nf-actions {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 0.9rem;
    margin-top: 2.25rem;
}

.nf-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.6rem;
    padding: 0.95rem 1.7rem;
    border-radius: 999px;
    font-weight: 700;
    font-size: 1rem;
    text-decoration: none;
    transition: transform 0.25s ease, box-shadow 0.25s ease, background 0.25s ease;
}

.nf-btn-primary {
    background: var(--nf-brand);
    color: #ffffff;
    box-shadow: 0 10px 30px -8px rgba(217, 70, 239, 0.6);
}

.nf-btn-primary:hover {
    color: #ffffff;
    transform: translateY(-2px);
    box-shadow: 0 16px 40px -10px rgba(217, 70, 239, 0.75);
}

.nf-btn-glass {
    border: 1px solid var(--nf-line);
    background: rgba(255, 255, 255, 0.045);
    color: #ffffff;
    backdrop-filter: blur(12px);
}

.nf-btn-glass:hover {
    color: #ffffff;
    background: rgba(255, 255, 255, 0.08);
    transform: translateY(-2px);
}

.nf-btn i {
    transition: transform 0.25s ease;
}

.nf-btn:hover i.fa-arrow-right {
    transform: translateX(4px);
}

.nf-pitch {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    margin: 2.5rem 0 0;
    font-size: 0.95rem;
    color: var(--nf-muted);
}

.nf-pitch i {
    color: #f0abfc;
}

.nf-pitch strong {
    color: #ffffff;
}

/* ---------- Pie ---------- */
.nf-footer {
    position: relative;
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 0.5rem 1.5rem;
    padding: 1.25rem;
    border-top: 1px solid var(--nf-line);
    font-size: 0.85rem;
    color: var(--nf-muted);
}

.nf-footer a {
    color: var(--nf-muted);
    text-decoration: none;
}

.nf-footer a:hover {
    color: #ffffff;
}

/* ---------- Entrada ---------- */
.nf-motion .nf-code,
.nf-motion .nf-url,
.nf-motion .nf-title,
.nf-motion .nf-text,
.nf-motion .nf-actions,
.nf-motion .nf-pitch {
    opacity: 0;
    animation: nf-rise 0.9s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
}

.nf-motion .nf-url     { animation-delay: 0.15s; }
.nf-motion .nf-title   { animation-delay: 0.25s; }
.nf-motion .nf-text    { animation-delay: 0.35s; }
.nf-motion .nf-actions { animation-delay: 0.45s; }
.nf-motion .nf-pitch   { animation-delay: 0.6s; }

/* ---------- Animaciones ---------- */
@keyframes nf-rise {
    from { opacity: 0; transform: translateY(24px); filter: blur(6px); }
    to   { opacity: 1; transform: none; filter: blur(0); }
}

@keyframes nf-drift {
    0%   { transform: translate(0, 0) scale(1); }
    50%  { transform: translate(-5vw, 4vw) scale(1.08); }
    100% { transform: translate(4vw, -3vw) scale(0.95); }
}

@keyframes nf-spin {
    to { transform: rotate(360deg); }
}

/* Las dos mitades intentan unirse y se vuelven a separar */
@keyframes nf-pull-left {
    0%, 100% { transform: translateX(-10px); }
    45%, 55% { transform: translateX(2px); }
}

@keyframes nf-pull-right {
    0%, 100% { transform: translateX(10px); }
    45%, 55% { transform: translateX(-2px); }
}

@keyframes nf-spark {
    0%, 35%, 65%, 100% { opacity: 0; transform: scale(0.6); }
    50%                { opacity: 1; transform: scale(1.15); }
}

/* ---------- Mobile ---------- */
@media (max-width: 600px) {
    .nf-top {
        padding-top: 1.1rem;
    }

    .nf-brand span {
        font-size: 1rem;
    }

    .nf-text {
        font-size: 1rem;
    }

    .nf-actions {
        width: 100%;
    }

    .nf-btn {
        flex: 1 1 100%;
    }

    .nf-pitch {
        font-size: 0.88rem;
    }
}
</style>

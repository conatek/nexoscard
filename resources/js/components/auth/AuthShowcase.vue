<template>
    <aside class="as-panel" :class="[`as-${variant}`, { 'as-motion': motionOk }]">
        <div class="as-aurora" aria-hidden="true">
            <span class="as-blob as-blob-1"></span>
            <span class="as-blob as-blob-2"></span>
        </div>
        <div class="as-grid" aria-hidden="true"></div>

        <router-link to="/inicio" class="as-brand" aria-label="Nexos Card">
            <img :src="img('logo-mark')" alt="">
            <span>NEXOS Card</span>
        </router-link>

        <div class="as-body">
            <!-- Tarjeta digital en SVG conectada a sus canales. En login está completa y en
                 línea; en registro se arma pieza por pieza, como la que el usuario va a crear. -->
            <div class="as-art" aria-hidden="true">
                <span class="as-halo"></span>

                <svg class="as-art-svg" viewBox="0 0 400 320" fill="none">
                    <defs>
                        <linearGradient id="as-grad" x1="0" y1="0" x2="400" y2="320" gradientUnits="userSpaceOnUse">
                            <stop offset="0" stop-color="#7c3aed" />
                            <stop offset="0.45" stop-color="#d946ef" />
                            <stop offset="0.75" stop-color="#fb7185" />
                            <stop offset="1" stop-color="#fdc5c0" />
                        </linearGradient>
                        <linearGradient id="as-card" x1="110" y1="50" x2="290" y2="280" gradientUnits="userSpaceOnUse">
                            <stop offset="0" stop-color="#ffffff" stop-opacity="0.16" />
                            <stop offset="1" stop-color="#ffffff" stop-opacity="0.04" />
                        </linearGradient>
                    </defs>

                    <!-- Conexiones hacia los canales -->
                    <g class="as-links" stroke="url(#as-grad)" stroke-width="2" stroke-linecap="round">
                        <path class="as-link" style="--k: 0" pathLength="100" d="M48 78 C 82 78, 88 118, 110 118" />
                        <path class="as-link" style="--k: 1" pathLength="100" d="M352 70 C 318 70, 310 104, 290 104" />
                        <path class="as-link" style="--k: 2" pathLength="100" d="M46 242 C 82 242, 88 218, 110 218" />
                        <path class="as-link" style="--k: 3" pathLength="100" d="M354 238 C 320 238, 312 200, 290 200" />
                    </g>

                    <!-- Tarjeta -->
                    <g class="as-card">
                        <rect x="110" y="50" width="180" height="220" rx="24" fill="url(#as-card)" stroke="url(#as-grad)" stroke-width="1.5" />

                        <!-- Foto -->
                        <g class="as-piece" style="--k: 0">
                            <circle cx="200" cy="104" r="29" fill="#0e0f2c" stroke="url(#as-grad)" stroke-width="3" />
                            <circle cx="200" cy="96" r="9" fill="#e9d5ff" />
                            <path d="M183 119 a17 13 0 0 1 34 0" fill="#e9d5ff" />
                        </g>
                        <!-- Indicador en línea (login) -->
                        <g v-if="variant === 'login'" class="as-online">
                            <circle class="as-online-ring" cx="222" cy="126" r="7" fill="#4ade80" />
                            <circle cx="222" cy="126" r="6" fill="#4ade80" stroke="#0e0f2c" stroke-width="2.5" />
                        </g>

                        <!-- Nombre y cargo -->
                        <rect class="as-piece as-grow" style="--k: 1" x="155" y="146" width="90" height="9" rx="4.5" fill="#ffffff" fill-opacity="0.9" />
                        <rect class="as-piece as-grow" style="--k: 2" x="170" y="162" width="60" height="6" rx="3" fill="#ffffff" fill-opacity="0.4" />

                        <!-- Botón de WhatsApp -->
                        <g class="as-piece" style="--k: 3">
                            <rect x="134" y="182" width="132" height="24" rx="12" fill="#25d366" />
                            <rect x="170" y="191" width="60" height="6" rx="3" fill="#ffffff" fill-opacity="0.85" />
                        </g>

                        <!-- Llamar / correo / web -->
                        <g class="as-piece" style="--k: 4">
                            <rect x="134" y="214" width="40" height="18" rx="9" fill="#ffffff" fill-opacity="0.14" />
                            <rect x="180" y="214" width="40" height="18" rx="9" fill="#ffffff" fill-opacity="0.14" />
                            <rect x="226" y="214" width="40" height="18" rx="9" fill="#ffffff" fill-opacity="0.14" />
                        </g>

                        <!-- Redes -->
                        <g class="as-piece" style="--k: 5" fill="url(#as-grad)">
                            <circle cx="164" cy="251" r="6" />
                            <circle cx="182" cy="251" r="6" />
                            <circle cx="200" cy="251" r="6" />
                            <circle cx="218" cy="251" r="6" />
                            <circle cx="236" cy="251" r="6" />
                        </g>
                    </g>
                </svg>

                <!-- Canales: HTML sobre el SVG para usar los íconos de FontAwesome -->
                <span
                    v-for="(n, i) in nodes"
                    :key="n.label"
                    class="as-node"
                    :style="{ left: `${n.x / 4}%`, top: `${n.y / 3.2}%`, '--c': n.color, '--k': i }"
                    :title="n.label"
                >
                    <i :class="n.icon"></i>
                </span>
            </div>

            <!-- Login: bienvenida y testimonios reales rotando -->
            <template v-if="variant === 'login'">
                <h2 class="as-title">Bienvenido de vuelta. <span class="as-gradient">Tu tarjeta te espera.</span></h2>
                <p class="as-text">Gestiona tus tarjetas digitales y haz crecer tu presencia en línea.</p>

                <figure class="as-quote">
                    <transition name="as-fade" mode="out-in">
                        <div :key="quoteIndex">
                            <blockquote>“{{ quote.quote }}”</blockquote>
                            <figcaption>
                                <span class="as-avatar"><img :src="img(quote.logo)" :alt="quote.company"></span>
                                <span>
                                    <strong>{{ quote.name }}</strong>
                                    <small>{{ quote.role }} · {{ quote.company }}</small>
                                </span>
                            </figcaption>
                        </div>
                    </transition>
                    <div class="as-dots" role="tablist" aria-label="Testimonios">
                        <button
                            v-for="(t, i) in testimonials"
                            :key="t.name"
                            type="button"
                            :class="{ 'is-active': i === quoteIndex }"
                            :aria-label="`Testimonio de ${t.name}`"
                            @click="showQuote(i)"
                        ></button>
                    </div>
                </figure>
            </template>

            <!-- Registro: los tres pasos, con el primero marcado, y lo que obtiene -->
            <template v-else>
                <h2 class="as-title">Tu presencia digital, <span class="as-gradient">en un solo enlace.</span></h2>

                <ol class="as-steps">
                    <li v-for="(s, i) in steps" :key="s.title" :class="{ 'is-current': i === 0 }">
                        <span class="as-step-dot"><i :class="s.icon" aria-hidden="true"></i></span>
                        <span class="as-step-text">
                            <strong>{{ s.title }}</strong>
                            <small v-if="i === 0">Estás aquí</small>
                        </span>
                    </li>
                </ol>

                <ul class="as-perks">
                    <li><i class="fas fa-gift" aria-hidden="true"></i> Prueba gratis por {{ trialDays }} días</li>
                    <li><i class="fas fa-credit-card" aria-hidden="true"></i> Sin tarjeta de crédito</li>
                    <li><i class="fas fa-book-open" aria-hidden="true"></i> Guía paso a paso para dejarla lista en minutos</li>
                </ul>
            </template>
        </div>

        <p class="as-foot">
            <i class="fas fa-shield-alt" aria-hidden="true"></i>
            Más de {{ clientsCount }} negocios y empresas ya confían en Nexos Card
        </p>
    </aside>
</template>

<script>
import api from '@/services/api.js';
import {
    FALLBACK, CLIENTS_COUNT, steps, testimonials, landingImage, fetchLandingData, loadFonts,
} from '@/views/public/landing/content.js';

const QUOTE_INTERVAL = 6500;

// Canales conectados a la tarjeta. x/y en coordenadas del viewBox (400x320) del SVG.
const NODES = [
    { label: 'WhatsApp directo', icon: 'fab fa-whatsapp',       color: '#25d366', x: 48,  y: 78 },
    { label: 'Código QR',        icon: 'fas fa-qrcode',         color: '#c4b5fd', x: 352, y: 70 },
    { label: 'Google Maps',      icon: 'fas fa-map-marker-alt', color: '#fb7185', x: 46,  y: 242 },
    { label: 'Compartir',        icon: 'fas fa-share-alt',      color: '#38bdf8', x: 354, y: 238 },
];

export default {
    name: 'AuthShowcase',

    props: {
        variant: {
            type: String,
            required: true,
            validator: (v) => ['login', 'register'].includes(v),
        },
    },

    data() {
        return {
            steps,
            testimonials,
            clientsCount: CLIENTS_COUNT,
            trialDays:    FALLBACK.trialDays,
            quoteIndex:   0,
            motionOk:     !window.matchMedia('(prefers-reduced-motion: reduce)').matches,
        };
    },

    computed: {
        nodes() {
            return NODES;
        },
        quote() {
            return this.testimonials[this.quoteIndex];
        },
    },

    async mounted() {
        loadFonts('lt-landing-fonts', 'https://fonts.googleapis.com/css2?family=Rammetto+One&display=swap');

        if (this.variant === 'login') {
            if (this.motionOk) this.startQuotes();
            return;
        }

        // Los días de prueba se editan desde el admin: el registro no debe prometer otro número.
        const data = await fetchLandingData(api);
        if (data) this.trialDays = data.trialDays;
    },

    beforeUnmount() {
        clearInterval(this.quoteTimer);
    },

    methods: {
        img: landingImage,

        startQuotes() {
            clearInterval(this.quoteTimer);
            this.quoteTimer = setInterval(() => {
                this.quoteIndex = (this.quoteIndex + 1) % this.testimonials.length;
            }, QUOTE_INTERVAL);
        },

        // Al elegir uno a mano, el ciclo se reinicia para no saltar enseguida al siguiente.
        showQuote(i) {
            this.quoteIndex = i;
            if (this.motionOk) this.startQuotes();
        },
    },
};
</script>

<style scoped>
.as-panel {
    --as-brand: linear-gradient(115deg, #7c3aed 0%, #d946ef 42%, #fb7185 72%, #fdc5c0 100%);
    --as-line: rgba(255, 255, 255, 0.1);
    --as-muted: #a6a9cc;

    position: relative;
    flex: 0 0 45%;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    padding: 2rem 3rem;
    background: #06071a;
    color: #eef0ff;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    overflow: hidden;
}

/* ---------- Fondo ---------- */
.as-aurora {
    position: absolute;
    inset: 0;
    pointer-events: none;
}

.as-blob {
    position: absolute;
    border-radius: 50%;
}

.as-blob-1 {
    width: 60vmax;
    height: 60vmax;
    top: -30vmax;
    right: -30vmax;
    background: radial-gradient(circle, rgba(217, 70, 239, 0.45), transparent 62%);
}

.as-blob-2 {
    width: 55vmax;
    height: 55vmax;
    bottom: -30vmax;
    left: -25vmax;
    background: radial-gradient(circle, rgba(124, 58, 237, 0.55), transparent 62%);
}

.as-motion .as-blob-1 { animation: as-drift 18s ease-in-out infinite alternate; }
.as-motion .as-blob-2 { animation: as-drift 22s ease-in-out infinite alternate-reverse; }

.as-grid {
    position: absolute;
    inset: 0;
    background-image:
        linear-gradient(rgba(255, 255, 255, 0.05) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255, 255, 255, 0.05) 1px, transparent 1px);
    background-size: 56px 56px;
    -webkit-mask-image: radial-gradient(ellipse at 50% 40%, #000 15%, transparent 70%);
    mask-image: radial-gradient(ellipse at 50% 40%, #000 15%, transparent 70%);
    pointer-events: none;
}

/* ---------- Marca ---------- */
.as-brand {
    position: relative;
    align-self: flex-start;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: #ffffff;
    text-decoration: none;
}

.as-brand:hover {
    color: #ffffff;
}

.as-brand img {
    width: 36px;
    height: 36px;
}

.as-brand span {
    font-family: 'Rammetto One', 'Outfit', sans-serif;
    font-size: 1.15rem;
    white-space: nowrap;
}

/* ---------- Cuerpo ---------- */
.as-body {
    position: relative;
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
    width: 100%;
    max-width: 460px;
    margin: 0 auto;
    padding: 1.5rem 0;
}

.as-art {
    position: relative;
    width: 100%;
    max-width: 400px;
    aspect-ratio: 400 / 320;
    margin: 0 auto 1.75rem;
}

.as-halo {
    position: absolute;
    inset: 12% 28%;
    border-radius: 50%;
    background: conic-gradient(from 0deg, #7c3aed, #d946ef, #fb7185, #fdc5c0, #38bdf8, #7c3aed);
    filter: blur(45px);
    opacity: 0.45;
}

.as-motion .as-halo {
    animation: as-spin 14s linear infinite;
}

.as-art-svg {
    position: relative;
    display: block;
    width: 100%;
    height: 100%;
    overflow: visible;
}

.as-card {
    filter: drop-shadow(0 24px 40px rgba(0, 0, 0, 0.5));
}

.as-motion .as-card {
    animation: as-float 6s ease-in-out infinite;
}

.as-piece {
    transform-box: fill-box;
    transform-origin: center;
}

.as-grow {
    transform-origin: left center;
}

.as-node {
    position: absolute;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 48px;
    height: 48px;
    border: 1px solid color-mix(in srgb, var(--c) 45%, transparent);
    border-radius: 16px;
    background: rgba(14, 15, 44, 0.8);
    backdrop-filter: blur(10px);
    color: var(--c);
    font-size: 1.25rem;
    box-shadow: 0 0 26px -6px var(--c), 0 12px 24px -12px rgba(0, 0, 0, 0.6);
    transform: translate(-50%, -50%);
}

.as-motion .as-node {
    animation: as-bob 5s ease-in-out infinite;
    animation-delay: calc(var(--k) * -1.25s);
}

/* ---------- Login: tarjeta completa, datos fluyendo hacia ella ---------- */
.as-login .as-link {
    stroke-dasharray: 3 7;
    opacity: 0.8;
}

.as-motion.as-login .as-link {
    animation: as-flow 1.6s linear infinite;
}

.as-online-ring {
    transform-box: fill-box;
    transform-origin: center;
}

.as-motion .as-online-ring {
    animation: as-ping 1.8s ease-out infinite;
}

/* ---------- Registro: la tarjeta se arma y las conexiones se dibujan ---------- */
.as-register .as-link {
    stroke-dasharray: 100;
}

.as-motion.as-register .as-piece {
    animation: as-build 7s cubic-bezier(0.2, 0.8, 0.2, 1) infinite backwards;
    animation-delay: calc(var(--k) * 0.35s);
}

.as-motion.as-register .as-grow {
    animation-name: as-build-x;
}

.as-motion.as-register .as-link {
    animation: as-draw 7s ease-out infinite backwards;
    animation-delay: calc(2.2s + var(--k) * 0.3s);
}

.as-motion.as-register .as-node {
    animation: as-node-in 7s cubic-bezier(0.2, 0.8, 0.2, 1) infinite backwards;
    animation-delay: calc(2.4s + var(--k) * 0.3s);
}

.as-title {
    margin: 0;
    text-align: center;
    font-family: 'Outfit', 'Inter', sans-serif;
    font-weight: 800;
    font-size: clamp(1.6rem, 2.4vw, 2.1rem);
    line-height: 1.15;
    letter-spacing: -0.02em;
    color: #ffffff;
}

.as-gradient {
    background: var(--as-brand);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
}

.as-text {
    margin: 0.85rem auto 0;
    max-width: 380px;
    text-align: center;
    line-height: 1.6;
    color: var(--as-muted);
}

/* ---------- Testimonios (login) ---------- */
.as-quote {
    margin: 2rem 0 0;
    padding: 1.4rem 1.5rem 1.1rem;
    border: 1px solid var(--as-line);
    border-radius: 22px;
    background: linear-gradient(160deg, rgba(255, 255, 255, 0.07), rgba(255, 255, 255, 0.02));
    backdrop-filter: blur(10px);
}

.as-quote blockquote {
    margin: 0;
    min-height: 4.8em;
    font-size: 0.98rem;
    line-height: 1.6;
    color: #e4e6ff;
}

.as-quote figcaption {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-top: 1rem;
}

.as-avatar {
    flex-shrink: 0;
    width: 42px;
    height: 42px;
    padding: 3px;
    border-radius: 50%;
    background: #ffffff;
    box-shadow: 0 0 0 2px rgba(217, 70, 239, 0.5);
}

.as-avatar img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: contain;
}

.as-quote figcaption strong {
    display: block;
    font-size: 0.92rem;
    color: #ffffff;
}

.as-quote figcaption small {
    font-size: 0.8rem;
    color: var(--as-muted);
}

.as-dots {
    display: flex;
    justify-content: center;
    gap: 0.4rem;
    margin-top: 1rem;
}

.as-dots button {
    width: 8px;
    height: 8px;
    padding: 0;
    border: 0;
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.25);
    cursor: pointer;
    transition: all 0.3s ease;
}

.as-dots button.is-active {
    width: 22px;
    background: var(--as-brand);
}

.as-fade-enter-active,
.as-fade-leave-active {
    transition: opacity 0.35s ease, transform 0.35s ease;
}

.as-fade-enter-from {
    opacity: 0;
    transform: translateY(8px);
}

.as-fade-leave-to {
    opacity: 0;
    transform: translateY(-8px);
}

/* ---------- Pasos (registro) ---------- */
.as-steps {
    position: relative;
    display: flex;
    justify-content: space-between;
    list-style: none;
    margin: 2rem 0 0;
    padding: 0;
}

/* Línea que une los pasos */
.as-steps::before {
    content: '';
    position: absolute;
    top: 21px;
    left: 16%;
    right: 16%;
    height: 2px;
    background: linear-gradient(90deg, #d946ef, rgba(255, 255, 255, 0.15));
}

.as-steps li {
    position: relative;
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.6rem;
    text-align: center;
}

.as-step-dot {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 44px;
    height: 44px;
    border: 1px solid var(--as-line);
    border-radius: 50%;
    background: #0e0f2c;
    color: var(--as-muted);
    font-size: 1rem;
}

.as-steps li.is-current .as-step-dot {
    border: 0;
    background: var(--as-brand);
    color: #ffffff;
    box-shadow: 0 0 0 6px rgba(217, 70, 239, 0.18), 0 0 30px -4px rgba(217, 70, 239, 0.8);
}

.as-motion .as-steps li.is-current .as-step-dot {
    animation: as-glow 2.4s ease-in-out infinite;
}

.as-step-text strong {
    display: block;
    font-size: 0.9rem;
    color: #ffffff;
}

.as-step-text small {
    display: inline-block;
    margin-top: 0.25rem;
    padding: 0.1rem 0.5rem;
    border-radius: 999px;
    background: rgba(217, 70, 239, 0.18);
    color: #f0abfc;
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.04em;
    text-transform: uppercase;
}

.as-perks {
    display: grid;
    gap: 0.7rem;
    list-style: none;
    margin: 2rem 0 0;
    padding: 1.25rem 1.4rem;
    border: 1px solid var(--as-line);
    border-radius: 20px;
    background: linear-gradient(160deg, rgba(255, 255, 255, 0.07), rgba(255, 255, 255, 0.02));
}

.as-perks li {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 0.95rem;
    color: #e4e6ff;
}

.as-perks i {
    width: 18px;
    text-align: center;
    color: #f0abfc;
}

/* ---------- Pie ---------- */
.as-foot {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    margin: 0;
    font-size: 0.85rem;
    color: var(--as-muted);
}

.as-foot i {
    color: #86efac;
}

/* ---------- Animaciones ---------- */
@keyframes as-drift {
    0%   { transform: translate(0, 0) scale(1); }
    50%  { transform: translate(-5vw, 4vw) scale(1.08); }
    100% { transform: translate(4vw, -3vw) scale(0.95); }
}

@keyframes as-spin {
    to { transform: rotate(360deg); }
}

@keyframes as-float {
    0%, 100% { translate: 0 0; }
    50%      { translate: 0 -12px; }
}

@keyframes as-bob {
    0%, 100% { translate: 0 0; }
    50%      { translate: 0 -8px; }
}

@keyframes as-flow {
    to { stroke-dashoffset: -20; }
}

@keyframes as-ping {
    0%   { transform: scale(1); opacity: 0.8; }
    100% { transform: scale(2.6); opacity: 0; }
}

/* Cada pieza aparece, se queda y se desvanece al final del ciclo */
@keyframes as-build {
    0%        { opacity: 0; transform: scale(0.6); }
    10%, 86%  { opacity: 1; transform: scale(1); }
    96%, 100% { opacity: 0; transform: scale(1); }
}

@keyframes as-build-x {
    0%        { opacity: 0; transform: scaleX(0); }
    10%, 86%  { opacity: 1; transform: scaleX(1); }
    96%, 100% { opacity: 0; transform: scaleX(1); }
}

@keyframes as-draw {
    0%        { stroke-dashoffset: 100; opacity: 1; }
    14%, 60%  { stroke-dashoffset: 0; opacity: 1; }
    66%, 100% { stroke-dashoffset: 0; opacity: 0; }
}

@keyframes as-node-in {
    0%        { opacity: 0; transform: translate(-50%, -50%) scale(0.5); }
    8%, 58%   { opacity: 1; transform: translate(-50%, -50%) scale(1); }
    64%, 100% { opacity: 0; transform: translate(-50%, -50%) scale(0.8); }
}

@keyframes as-glow {
    0%, 100% { box-shadow: 0 0 0 6px rgba(217, 70, 239, 0.18), 0 0 30px -4px rgba(217, 70, 239, 0.8); }
    50%      { box-shadow: 0 0 0 10px rgba(217, 70, 239, 0.08), 0 0 40px -2px rgba(217, 70, 239, 0.9); }
}

/* Pantallas bajas: se achica el celular para que el texto no quede cortado */
@media (max-height: 820px) {
    .as-panel {
        padding-top: 1.5rem;
        padding-bottom: 1.5rem;
    }

    .as-body {
        padding: 0.75rem 0;
    }

    .as-art {
        max-width: 270px;
        margin-bottom: 0.75rem;
    }

    .as-steps,
    .as-perks,
    .as-quote {
        margin-top: 1.25rem;
    }

    .as-perks {
        padding: 1rem 1.25rem;
    }

    .as-quote blockquote {
        min-height: 0;
    }
}

/* En tablet y celular el panel se oculta y el formulario ocupa toda la pantalla */
@media (max-width: 992px) {
    .as-panel {
        display: none;
    }
}
</style>

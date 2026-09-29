<template>
    <div class="nl-page">
        <!-- ===================== Hero ===================== -->
        <section class="nl-hero">
            <div class="nl-container">
                <router-link to="/landing" class="nl-brand" aria-label="Nexos Card">
                    <img :src="img('logo-mark')" alt="" class="nl-brand-mark">
                    <span class="nl-brand-text">
                        <span class="nl-brand-name">NEXOS Card</span>
                        <span class="nl-brand-tag">tarjetas de contacto interactivas</span>
                    </span>
                </router-link>

                <div class="nl-hero-grid">
                    <div class="nl-hero-visual">
                        <img :src="img('hero-phone')" alt="Tarjeta digital Nexos Card en un celular" class="nl-hero-phone">
                    </div>

                    <div class="nl-hero-copy">
                        <h1 class="nl-hero-title">Tu presencia profesional y comercial en un solo enlace</h1>
                        <ul class="nl-hero-list">
                            <li>✓ Acceso directo e instantáneo sin descargar aplicaciones ni archivos.</li>
                            <li>✓ Enlaces directos a WhatsApp, catálogo y redes.</li>
                            <li>✓ Actualiza tus datos en tiempo real sin reimprimir.</li>
                        </ul>

                        <div class="nl-hero-actions">
                            <router-link to="/register" class="nl-btn-white">Crear Mi Tarjeta</router-link>
                            <p class="nl-hero-note">*Pruébala <strong>GRATIS</strong>. No necesitas tarjeta de crédito.</p>
                            <a :href="guideUrl" target="_blank" rel="noopener" class="nl-chip-outline">Guía rápida PDF paso a paso</a>
                            <router-link :to="demoUrl" class="nl-btn-ghost">Ver Demo en Vivo</router-link>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ===================== Todo lo que incluye ===================== -->
        <section class="nl-section nl-white">
            <div class="nl-container">
                <header class="nl-head">
                    <span class="nl-badge"><img :src="img('badge-mark')" alt="">TODO LO QUE INCLUYE</span>
                    <h2 class="nl-title">Todo lo que necesitas para<br><span class="nl-accent">destacar tu negocio</span></h2>
                    <p class="nl-sub">Herramientas poderosas para mostrar tu negocio de forma profesional y conectar con más clientes</p>
                </header>

                <div class="nl-features">
                    <article v-for="f in features" :key="f.title" class="nl-feature">
                        <i :class="f.icon" class="nl-feature-icon" aria-hidden="true"></i>
                        <h3>{{ f.title }}</h3>
                        <p>{{ f.text }}</p>
                    </article>
                </div>
            </div>
        </section>

        <!-- ===================== Proceso simple ===================== -->
        <section class="nl-section nl-gray">
            <div class="nl-container">
                <header class="nl-head">
                    <span class="nl-badge"><img :src="img('badge-mark')" alt="">PROCESO SIMPLE</span>
                    <h2 class="nl-title">¿Cómo funciona <span class="nl-accent">Nexos Card?</span></h2>
                    <p class="nl-sub">Crear tu tarjeta digital es rápido, sencillo y no necesitas conocimientos técnicos</p>
                </header>

                <div class="nl-steps">
                    <template v-for="(s, i) in steps" :key="s.title">
                        <article class="nl-step">
                            <span class="nl-step-num">{{ i + 1 }}</span>
                            <span class="nl-step-icon"><i :class="s.icon" aria-hidden="true"></i></span>
                            <h3>{{ s.title }}</h3>
                            <p>{{ s.text }}</p>
                        </article>
                        <i v-if="i < steps.length - 1" class="fas fa-long-arrow-alt-right nl-step-arrow" aria-hidden="true"></i>
                    </template>
                </div>

                <div class="nl-center">
                    <router-link to="/register" class="nl-btn-start">
                        EMPIEZA GRATIS <i class="fas fa-long-arrow-alt-right" aria-hidden="true"></i>
                    </router-link>
                    <p class="nl-small"><i class="fas fa-shield-alt" aria-hidden="true"></i> Sin tarjeta de crédito - Cancela cuando quieras</p>
                    <p class="nl-small nl-dark">✓ Incluye guía paso a paso para configurar tu tarjeta en minutos.</p>
                </div>
            </div>
        </section>

        <!-- ===================== Testimonios ===================== -->
        <section class="nl-section nl-white">
            <div class="nl-container">
                <header class="nl-head">
                    <span class="nl-badge"><img :src="img('badge-mark')" alt="">TESTIMONIOS REALES</span>
                    <h2 class="nl-title">Lo que dicen <span class="nl-accent">nuestros clientes</span></h2>
                    <p class="nl-sub">Profesionales y emprendedores ya están creciendo con su tarjeta digital Nexos Card</p>
                </header>

                <div class="nl-testimonials">
                    <article v-for="t in testimonials" :key="t.name" class="nl-testimonial">
                        <div class="nl-testimonial-top">
                            <i class="fas fa-quote-left nl-quote" aria-hidden="true"></i>
                            <span class="nl-stars" aria-label="5 estrellas">
                                <i v-for="n in 5" :key="n" class="fas fa-star" aria-hidden="true"></i>
                            </span>
                        </div>
                        <p class="nl-testimonial-text">“{{ t.quote }}”</p>
                        <hr>
                        <div class="nl-testimonial-author">
                            <img :src="img(t.logo)" :alt="t.company">
                            <div>
                                <strong>{{ t.name }}</strong>
                                <span>{{ t.company }}</span>
                                <span>{{ t.role }}</span>
                                <span class="nl-place"><i class="fas fa-map-marker-alt" aria-hidden="true"></i> {{ t.place }}</span>
                            </div>
                        </div>
                    </article>
                </div>

                <div class="nl-trust nl-trust-card">
                    <span class="nl-trust-icon"><i class="fas fa-shield-alt" aria-hidden="true"></i></span>
                    <p>Impulsando las ventas de negocios independientes y profesionales</p>
                </div>
            </div>
        </section>

        <!-- ===================== Plan único ===================== -->
        <section class="nl-section nl-gray">
            <div class="nl-container">
                <header class="nl-head">
                    <span class="nl-badge"><img :src="img('badge-mark')" alt="">PLAN ÚNICO</span>
                    <h2 class="nl-title">Empieza hoy con<br><span class="nl-accent">{{ pricing.name }}</span></h2>
                    <p class="nl-sub">Todo lo que necesitas para tu presencia profesional en un solo pago simple</p>
                </header>

                <div class="nl-plan">
                    <div class="nl-plan-price">
                        <h3 class="nl-plan-name">{{ pricing.name.toUpperCase() }}</h3>
                        <p v-if="pricing.hasOffer" class="nl-plan-before">Antes <s>${{ money(pricing.regular) }}</s></p>
                        <p class="nl-plan-today">Hoy</p>
                        <p class="nl-plan-amount">
                            <span class="nl-plan-currency">$</span>{{ money(pricing.price) }}<span class="nl-plan-period">/año</span>
                        </p>
                        <p class="nl-plan-after">(después de tu prueba)</p>
                        <p v-if="pricing.hasOffer" class="nl-plan-save">
                            Ahorras ${{ money(pricing.regular - pricing.price) }} ({{ pricing.discount }}%)
                        </p>

                        <router-link to="/register" class="nl-btn-gradient nl-btn-lg">
                            Probar {{ trialDays }} Días Gratis <i class="fas fa-long-arrow-alt-right" aria-hidden="true"></i>
                        </router-link>

                        <ul class="nl-plan-perks">
                            <li><i class="fas fa-shield-alt" aria-hidden="true"></i> Sin cláusulas de permanencia</li>
                            <li><i class="fas fa-sync-alt" aria-hidden="true"></i> Actualizaciones incluidas</li>
                            <li><i class="fas fa-pencil-alt" aria-hidden="true"></i> Tu información siempre editable</li>
                        </ul>
                    </div>

                    <div class="nl-plan-includes">
                        <h3>Todo lo que incluye tu plan</h3>
                        <ul>
                            <li v-for="item in planItems" :key="item.text">
                                <span class="nl-plan-icon"><i :class="item.icon" aria-hidden="true"></i></span>
                                {{ item.text }}
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="nl-trust">
                    <span class="nl-trust-icon nl-trust-icon-gray"><i class="fas fa-shield-alt" aria-hidden="true"></i></span>
                    <p>Más de 25 negocios y empresas ya confían en Nexos Card</p>
                </div>

                <div class="nl-promo">
                    <img :src="img('phone-promo')" alt="Ejemplo de tarjeta Nexos Card" class="nl-promo-phone">
                    <div class="nl-promo-copy">
                        <h2 class="nl-title nl-title-left">Tu tarjeta digital<br><span class="nl-accent">trabaja por ti 24/7</span></h2>
                        <p class="nl-promo-text">Comparte tu información en segundos, genera confianza y recibe más clientes</p>
                        <router-link to="/register" class="nl-btn-gradient">Comenzar Prueba Gratis</router-link>
                    </div>
                </div>
            </div>
        </section>

        <!-- ===================== Preguntas frecuentes ===================== -->
        <section class="nl-section nl-white">
            <div class="nl-container">
                <header class="nl-head">
                    <span class="nl-badge"><img :src="img('badge-mark')" alt="">PREGUNTAS FRECUENTES</span>
                    <h2 class="nl-title">Resuelve <span class="nl-accent">tus dudas</span></h2>
                    <p class="nl-sub">Todo lo que necesitas saber sobre tu tarjeta digital Nexos Card</p>
                </header>

                <div class="nl-faq">
                    <aside class="nl-faq-aside">
                        <h3>¿Tienes otra pregunta?</h3>
                        <p>Estamos aquí para ayudarte. Escríbenos por WhatsApp y te responderemos con gusto.</p>
                        <a :href="whatsappUrl" target="_blank" rel="noopener" class="nl-btn-outline-purple">
                            <i class="fab fa-whatsapp" aria-hidden="true"></i> Escríbenos por WhatsApp
                        </a>
                        <div class="nl-faq-art">
                            <img :src="img('faq-bubbles')" alt="">
                        </div>
                    </aside>

                    <div class="nl-faq-list">
                        <div v-for="q in faqs" :key="q.q" class="nl-faq-item">
                            <h3>{{ q.q }}</h3>
                            <p>{{ q.a }}</p>
                        </div>
                        <a :href="guideUrl" target="_blank" rel="noopener" class="nl-chip-purple">Guía rápida PDF paso a paso</a>
                    </div>
                </div>

                <div class="nl-join">
                    <img :src="img('phone-advisor')" alt="Ejemplo de tarjeta Nexos Card" class="nl-join-phone">
                    <div class="nl-join-copy">
                        <h2 class="nl-title nl-title-left">Únete a los profesionales y negocios que ya usan <span class="nl-accent">Nexos Card</span></h2>
                        <p class="nl-promo-text">Crea tu tarjeta digital hoy y empieza a conectar más y mejor.</p>
                        <router-link to="/register" class="nl-btn-gradient">Comenzar Prueba Gratis</router-link>
                        <p class="nl-small"><i class="fas fa-shield-alt" aria-hidden="true"></i> Prueba gratis por {{ trialDays }} días • Sin tarjeta de crédito</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ===================== Contacto ===================== -->
        <section class="nl-section nl-gray">
            <div class="nl-container">
                <header class="nl-head">
                    <span class="nl-badge"><img :src="img('badge-mark')" alt="">CONTACTO</span>
                    <h2 class="nl-title">Estamos aquí <span class="nl-accent">para ayudarte</span></h2>
                </header>

                <div class="nl-contact">
                    <div class="nl-contact-list">
                        <p class="nl-contact-intro">
                            Nuestro equipo está listo para resolver todas tus dudas y ayudarte a crear tu tarjeta digital perfecta.
                        </p>

                        <div v-for="c in contactItems" :key="c.title" class="nl-contact-item">
                            <span class="nl-contact-icon" :style="{ background: c.color }">
                                <i :class="c.icon" aria-hidden="true"></i>
                            </span>
                            <div>
                                <h3>{{ c.title }}</h3>
                                <p>{{ c.text }}</p>
                                <router-link v-if="c.to" :to="c.to" class="nl-contact-btn" :style="{ background: c.color }">
                                    {{ c.label }} <i class="fas fa-long-arrow-alt-right" aria-hidden="true"></i>
                                </router-link>
                                <a v-else :href="c.href" target="_blank" rel="noopener" class="nl-contact-btn" :style="{ background: c.color }">
                                    {{ c.label }} <i class="fas fa-long-arrow-alt-right" aria-hidden="true"></i>
                                </a>
                                <p v-if="c.note" class="nl-contact-note"><strong>Importante:</strong> {{ c.note }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="nl-contact-visual">
                        <div class="nl-contact-phone-wrap">
                            <img :src="img('phone-contact')" alt="Ejemplo de tarjeta Nexos Card" class="nl-contact-phone">
                        </div>
                        <router-link to="/register" class="nl-btn-gradient nl-btn-xl">
                            Empieza Gratis <i class="fas fa-long-arrow-alt-right" aria-hidden="true"></i>
                        </router-link>
                    </div>
                </div>

                <div class="nl-banner">
                    <img :src="img('professionals')" alt="Profesionales de distintos rubros" class="nl-banner-photo">
                    <div class="nl-banner-copy">
                        <h3>Muchos profesionales y negocios ya están conectados más y mejor</h3>
                        <p>Únete a ellos y lleva tu negocio al siguiente nivel</p>
                        <router-link to="/register" class="nl-btn-gradient">Comenzar Prueba Gratis</router-link>
                    </div>
                </div>
            </div>
        </section>

        <!-- ===================== Footer ===================== -->
        <footer class="nl-footer">
            <div class="nl-container">
                <div class="nl-footer-top">
                    <div>
                        <div class="nl-footer-brand">
                            <img :src="img('logo-mark-silver')" alt="">
                            <span>Nexos Card</span>
                        </div>
                        <p>La tarjeta digital que conecta tu negocio.</p>
                    </div>
                    <div class="nl-footer-social">
                        <span>Síguenos</span>
                        <a v-for="s in socials" :key="s.label" :href="s.href" target="_blank" rel="noopener" :aria-label="s.label">
                            <i :class="s.icon" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
                <div class="nl-footer-bottom">
                    <span>© {{ year }} Nexos Card - Todos los derechos reservados.</span>
                    <router-link to="/politica-privacidad">Política de Privacidad</router-link>
                    <router-link to="/terminos-condiciones">Términos y Condiciones</router-link>
                </div>
            </div>
        </footer>
    </div>
</template>

<script>
import api from '@/services/api.js';
import {
    FALLBACK, DEMO_URL, features, steps, testimonials, planItems, socials,
    money, landingImage, buildFaqs, buildContactItems,
    fetchLandingData, defaultPricing, loadFonts,
} from './landing/content.js';

// Colores de los botones de contacto de la página original.
const CONTACT_COLORS = {
    whatsapp: '#4cc25a',
    email:    '#6a1fb5',
    demo:     '#ef3b3b',
    guide:    '#1e3bb3',
};

export default {
    name: 'LandingPage',

    data() {
        return {
            demoUrl:   DEMO_URL,
            pricing:   defaultPricing(),
            trialDays: FALLBACK.trialDays,
            whatsapp:  FALLBACK.whatsapp,
            email:     FALLBACK.email,
            guideUrl:  FALLBACK.guideUrl,
            features,
            steps,
            testimonials,
            planItems,
            socials,
        };
    },

    computed: {
        year() {
            return new Date().getFullYear();
        },
        whatsappUrl() {
            return `https://wa.me/${this.whatsapp}`;
        },
        faqs() {
            return buildFaqs({ planName: this.pricing.name, price: this.pricing.price, trialDays: this.trialDays });
        },
        contactItems() {
            return buildContactItems({ whatsappUrl: this.whatsappUrl, email: this.email, guideUrl: this.guideUrl })
                .map((item) => ({ ...item, color: CONTACT_COLORS[item.key] }));
        },
    },

    async mounted() {
        document.title = 'NEXOS Card | Tu tarjeta digital profesional';
        loadFonts('nl-landing-fonts', 'https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@500;600;700;800&family=Rammetto+One&display=swap');

        const data = await fetchLandingData(api);
        if (data) {
            if (data.pricing) this.pricing = data.pricing;
            this.trialDays = data.trialDays;
            this.whatsapp  = data.whatsapp;
            this.email     = data.email;
            this.guideUrl  = data.guideUrl;
        }
    },

    methods: {
        img: landingImage,
        money,
    },
};
</script>

<style scoped>
/* ---------- Base ---------- */
/* :where() deja la regla sin especificidad, para que los tamaños de cada imagen la ganen. */
.nl-page {
    --nl-purple: #8f1eae;
    --nl-purple-dark: #6a1fb5;
    --nl-gray: #d9d9d9;
    --nl-badge: #b3b3b3;
    --nl-ink: #111111;
    --nl-text: #545454;
    --nl-cta: linear-gradient(90deg, #0f1a3a 0%, #6a3fc4 38%, #f35fd0 72%, #fdc5c0 100%);

    font-family: 'Open Sans', -apple-system, BlinkMacSystemFont, sans-serif;
    color: var(--nl-ink);
    background: #ffffff;
    overflow-x: hidden;
}

:where(.nl-page) img {
    max-width: 100%;
}

.nl-container {
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1rem;
}

.nl-section {
    padding: 5rem 0;
}

.nl-white {
    background: #ffffff;
}

.nl-gray {
    background: var(--nl-gray);
}

.nl-center {
    text-align: center;
}

.nl-head {
    text-align: center;
    max-width: 760px;
    margin: 0 auto 3rem;
}

.nl-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.45rem 1.25rem 0.45rem 0.75rem;
    border-radius: 14px;
    background: var(--nl-badge);
    color: var(--nl-purple);
    font-family: 'Hanken Grotesk', 'Open Sans', sans-serif;
    font-weight: 700;
    font-size: 1.35rem;
    letter-spacing: 0.01em;
}

.nl-badge img {
    width: 36px;
    height: 36px;
}

.nl-title {
    margin: 1.5rem 0 0;
    font-family: 'Hanken Grotesk', 'Open Sans', sans-serif;
    font-weight: 700;
    font-size: 2.6rem;
    line-height: 1.3;
    color: var(--nl-ink);
}

.nl-title-left {
    margin-top: 0;
    text-align: left;
}

.nl-accent {
    color: var(--nl-purple);
}

.nl-sub {
    margin: 1.5rem auto 0;
    max-width: 720px;
    font-size: 1.3rem;
    line-height: 1.5;
    color: var(--nl-ink);
}

.nl-small {
    margin: 0.75rem 0 0;
    font-size: 0.95rem;
    color: var(--nl-text);
}

.nl-dark {
    color: var(--nl-ink);
}

/* ---------- Botones ---------- */
.nl-btn-white,
.nl-btn-ghost,
.nl-btn-gradient,
.nl-btn-start,
.nl-btn-outline-purple,
.nl-chip-outline,
.nl-chip-purple,
.nl-contact-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    text-decoration: none;
    transition: all 0.2s ease;
}

.nl-btn-white,
.nl-btn-ghost {
    width: 100%;
    max-width: 380px;
    min-height: 92px;
    border-radius: 999px;
    font-size: 2rem;
    font-weight: 600;
}

.nl-btn-white {
    background: #ffffff;
    color: #5b1c9a;
}

.nl-btn-ghost {
    border: 4px solid #ffffff;
    color: #ffffff;
}

.nl-btn-white:hover,
.nl-btn-ghost:hover,
.nl-btn-gradient:hover,
.nl-btn-start:hover,
.nl-contact-btn:hover {
    transform: translateY(-2px);
}

.nl-btn-ghost:hover {
    background: rgba(255, 255, 255, 0.12);
    color: #ffffff;
}

.nl-btn-white:hover {
    color: #5b1c9a;
}

.nl-chip-outline {
    padding: 0.3rem 0.75rem;
    border: 3px solid #ffffff;
    border-radius: 999px;
    color: #ffffff;
    font-weight: 700;
    font-size: 1rem;
}

.nl-chip-outline:hover {
    background: rgba(255, 255, 255, 0.15);
    color: #ffffff;
}

.nl-chip-purple {
    margin-top: 0.5rem;
    padding: 0.35rem 0.75rem;
    border: 3px solid var(--nl-purple);
    border-radius: 999px;
    color: var(--nl-purple);
    font-weight: 700;
    font-size: 1rem;
}

.nl-chip-purple:hover {
    background: var(--nl-purple);
    color: #ffffff;
}

.nl-btn-gradient {
    padding: 1rem 1.5rem;
    border-radius: 22px;
    background: var(--nl-cta);
    color: #ffffff;
    font-family: 'Rammetto One', 'Hanken Grotesk', sans-serif;
    font-size: 1.6rem;
    box-shadow: 0 8px 18px rgba(0, 0, 0, 0.25);
}

.nl-btn-gradient:hover {
    color: #ffffff;
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.3);
}

.nl-btn-lg {
    width: 100%;
    max-width: 540px;
    padding: 2.25rem 1.5rem;
}

.nl-btn-xl {
    width: 100%;
    max-width: 460px;
    padding: 2.4rem 1.5rem;
    font-family: 'Hanken Grotesk', 'Open Sans', sans-serif;
    font-weight: 500;
    font-size: 2.2rem;
}

.nl-btn-start {
    padding: 1rem 2.5rem;
    border-radius: 18px;
    background: linear-gradient(90deg, #5f6df2, #e46fc9);
    color: #ffffff;
    font-family: 'Rammetto One', 'Hanken Grotesk', sans-serif;
    font-size: 1.75rem;
    box-shadow: 0 8px 18px rgba(0, 0, 0, 0.25);
}

.nl-btn-start:hover {
    color: #ffffff;
}

.nl-btn-outline-purple {
    padding: 1.1rem 2.25rem;
    border: 6px solid var(--nl-purple-dark);
    border-radius: 999px;
    color: var(--nl-purple);
    font-weight: 700;
    font-size: 1.5rem;
}

.nl-btn-outline-purple i {
    font-size: 2.4rem;
}

.nl-btn-outline-purple:hover {
    background: #f5f3ff;
    color: var(--nl-purple);
}

/* ---------- Hero ---------- */
.nl-hero {
    background: linear-gradient(30deg, #0f1a3a 8%, #6a3fc4 38%, #f35fd0 68%, #fdc5c0 96%);
    color: #ffffff;
    padding: 2.5rem 0 0;
}

.nl-brand {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    text-decoration: none;
    color: #ffffff;
}

.nl-brand:hover {
    color: #ffffff;
}

.nl-brand-mark {
    width: 110px;
    height: 110px;
}

.nl-brand-text {
    display: flex;
    flex-direction: column;
}

.nl-brand-name {
    font-family: 'Rammetto One', 'Hanken Grotesk', sans-serif;
    font-size: 3.2rem;
    line-height: 1;
}

.nl-brand-tag {
    font-family: 'Rammetto One', 'Hanken Grotesk', sans-serif;
    font-size: 1rem;
    letter-spacing: 0.02em;
}

.nl-hero-grid {
    display: grid;
    grid-template-columns: 1.1fr 1fr;
    align-items: center;
    gap: 2rem;
}

.nl-hero-visual {
    align-self: end;
}

.nl-hero-phone {
    display: block;
    width: 100%;
    max-width: 620px;
    margin-bottom: -1px;
}

.nl-hero-title {
    margin: 0;
    font-weight: 700;
    font-size: 2.3rem;
    line-height: 1.4;
}

.nl-hero-list {
    list-style: none;
    margin: 2.5rem 0 0;
    padding: 0;
    font-size: 1.65rem;
    line-height: 1.4;
}

.nl-hero-list li + li {
    margin-top: 1.75rem;
}

.nl-hero-actions {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.75rem;
    margin: 3.5rem 0 3rem;
}

.nl-hero-note {
    margin: 0;
    font-size: 1.05rem;
}

.nl-hero-actions .nl-btn-ghost {
    margin-top: 3.5rem;
}

/* ---------- Todo lo que incluye ---------- */
.nl-features {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.25rem;
    max-width: 800px;
    margin: 0 auto;
}

.nl-feature {
    background: var(--nl-gray);
    border-radius: 44px;
    padding: 1.1rem 0.75rem 1.25rem;
    text-align: center;
    min-height: 185px;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.nl-feature-icon {
    font-size: 3.6rem;
    color: var(--nl-purple);
    height: 76px;
    display: flex;
    align-items: center;
}

.nl-feature h3 {
    margin: 0.75rem 0 0.35rem;
    font-size: 1.05rem;
    font-weight: 700;
    color: var(--nl-text);
}

.nl-feature p {
    margin: 0;
    font-size: 0.9rem;
    line-height: 1.35;
    color: var(--nl-text);
}

/* ---------- Pasos ---------- */
.nl-steps {
    display: flex;
    align-items: stretch;
    justify-content: center;
    gap: 0.5rem;
    margin-bottom: 3.5rem;
    padding-top: 2.5rem;
}

.nl-step {
    position: relative;
    flex: 0 1 280px;
    min-height: 365px;
    background: #ffffff;
    border-radius: 44px;
    padding: 4.25rem 1.5rem 2rem;
    text-align: center;
    box-shadow: 0 18px 40px rgba(0, 0, 0, 0.12);
}

.nl-step-num {
    position: absolute;
    top: -44px;
    left: 50%;
    transform: translateX(-50%);
    width: 78px;
    height: 78px;
    border-radius: 50%;
    background: #7022b5;
    color: #ffffff;
    font-size: 2.4rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
}

.nl-step-icon {
    width: 128px;
    height: 128px;
    margin: 0 auto;
    border-radius: 50%;
    background: var(--nl-gray);
    color: var(--nl-purple);
    font-size: 3.6rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.nl-step h3 {
    margin: 2rem 0 1rem;
    font-size: 1.4rem;
    font-weight: 700;
    color: var(--nl-text);
}

.nl-step p {
    margin: 0;
    font-size: 1.05rem;
    line-height: 1.45;
    color: var(--nl-text);
}

.nl-step-arrow {
    align-self: center;
    font-size: 2.5rem;
    color: var(--nl-purple);
    flex: 0 0 auto;
    margin: 0 0.5rem;
}

/* ---------- Testimonios ---------- */
.nl-testimonials {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 3rem;
}

.nl-testimonial {
    background: #ffffff;
    border-radius: 44px;
    padding: 2.25rem 1.25rem 2rem;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.12);
    display: flex;
    flex-direction: column;
}

.nl-testimonial-top {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
}

.nl-quote {
    font-size: 4rem;
    color: #d9d9d9;
    line-height: 1;
}

.nl-stars {
    color: #ffc93c;
    font-size: 1.6rem;
    letter-spacing: 0.1rem;
}

.nl-testimonial-text {
    margin: 1.25rem 0 0;
    font-size: 1.35rem;
    line-height: 1.45;
    color: var(--nl-text);
    flex: 1;
}

.nl-testimonial hr {
    width: 92px;
    border: 0;
    border-top: 3px solid #c8c8c8;
    margin: 1.75rem 0 1.75rem 0.25rem;
}

.nl-testimonial-author {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.nl-testimonial-author img {
    width: 110px;
    height: 110px;
    flex-shrink: 0;
    border-radius: 50%;
    object-fit: contain;
}

.nl-testimonial-author div {
    display: flex;
    flex-direction: column;
    color: var(--nl-text);
    font-size: 1.05rem;
    line-height: 1.45;
}

.nl-testimonial-author strong {
    font-size: 1.5rem;
}

.nl-place {
    margin-top: 0.5rem;
    font-size: 0.95rem;
}

.nl-place i {
    color: var(--nl-purple);
    font-size: 1.3rem;
    margin-right: 0.2rem;
}

.nl-trust {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1.25rem;
    margin: 5rem 0;
}

.nl-trust p {
    margin: 0;
    max-width: 460px;
    font-size: 1.6rem;
    font-weight: 700;
    line-height: 1.4;
    color: var(--nl-text);
}

.nl-trust-card {
    background: #ffffff;
    border-radius: 44px;
    padding: 1.75rem 2rem;
    margin-bottom: 0;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.12);
}

.nl-trust-icon {
    width: 112px;
    height: 112px;
    flex-shrink: 0;
    border-radius: 50%;
    background: #d9b8ec;
    color: var(--nl-purple);
    font-size: 3.4rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.nl-trust-icon-gray {
    background: #ececec;
}

/* ---------- Plan ---------- */
.nl-plan {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    background: #ffffff;
    border-radius: 44px;
    padding: 4rem 4rem 4.5rem;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.18);
}

.nl-plan-price {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
}

.nl-plan-name {
    margin: 0 0 1.5rem 5rem;
    font-family: 'Hanken Grotesk', 'Open Sans', sans-serif;
    font-weight: 700;
    font-size: 2.5rem;
    color: var(--nl-purple);
}

.nl-plan-before {
    margin: 0 0 1rem 9rem;
    font-family: 'Hanken Grotesk', 'Open Sans', sans-serif;
    font-size: 2.5rem;
}

.nl-plan-before s {
    text-decoration-color: #ef3b3b;
    text-decoration-thickness: 3px;
}

.nl-plan-today {
    margin: 0 0 0 2.5rem;
    font-family: 'Hanken Grotesk', 'Open Sans', sans-serif;
    font-size: 2.5rem;
}

.nl-plan-amount {
    margin: 0;
    font-family: 'Hanken Grotesk', 'Open Sans', sans-serif;
    font-weight: 800;
    font-size: 7.3rem;
    line-height: 1.05;
    color: var(--nl-purple);
    white-space: nowrap;
}

.nl-plan-currency {
    font-size: 4.5rem;
    vertical-align: 1.5rem;
}

.nl-plan-period {
    margin-left: 0.5rem;
    font-weight: 400;
    font-size: 2.5rem;
    color: var(--nl-ink);
}

.nl-plan-after {
    align-self: flex-end;
    margin: 0 1rem 2rem 0;
    font-weight: 700;
    font-size: 1.05rem;
}

.nl-plan-save {
    margin: 0 0 3.5rem;
    padding: 1.25rem 2.25rem;
    border-radius: 30px;
    background: #cdf0c4;
    color: #007a3f;
    font-family: 'Hanken Grotesk', 'Open Sans', sans-serif;
    font-weight: 700;
    font-size: 2.3rem;
}

.nl-plan-perks {
    list-style: none;
    padding: 0;
    margin: 4rem 0 0;
    font-family: 'Hanken Grotesk', 'Open Sans', sans-serif;
    font-size: 1.9rem;
    color: #666666;
}

.nl-plan-perks li {
    display: flex;
    align-items: center;
    gap: 1.25rem;
}

.nl-plan-perks li + li {
    margin-top: 2.25rem;
}

.nl-plan-perks i {
    width: 64px;
    text-align: center;
    font-size: 3.2rem;
    color: #555555;
}

.nl-plan-includes h3 {
    margin: 0 0 1.5rem;
    font-family: 'Hanken Grotesk', 'Open Sans', sans-serif;
    font-weight: 700;
    font-size: 2.5rem;
}

.nl-plan-includes ul {
    list-style: none;
    padding: 0;
    margin: 0;
    font-family: 'Hanken Grotesk', 'Open Sans', sans-serif;
    font-size: 1.9rem;
}

.nl-plan-includes li {
    display: flex;
    align-items: center;
    gap: 1.25rem;
}

.nl-plan-includes li + li {
    margin-top: 1.25rem;
}

.nl-plan-icon {
    width: 86px;
    height: 86px;
    flex-shrink: 0;
    border-radius: 50%;
    background: #c96be3;
    color: #7a1d9c;
    font-size: 2.3rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.nl-promo,
.nl-join {
    display: grid;
    grid-template-columns: 1fr 1.1fr;
    align-items: center;
    gap: 2rem;
    border-radius: 44px;
    padding: 2rem 4rem;
}

.nl-promo {
    background: #ffffff;
}

.nl-join {
    margin-top: 5rem;
    background: var(--nl-badge);
}

.nl-promo-phone,
.nl-join-phone {
    display: block;
    width: 100%;
    max-width: 300px;
    margin: 0 auto;
    transform: rotate(-8deg);
}

.nl-promo-copy,
.nl-join-copy {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
}

.nl-promo-text {
    margin: 1.5rem 0 2.5rem;
    max-width: 480px;
    font-family: 'Hanken Grotesk', 'Open Sans', sans-serif;
    font-weight: 600;
    font-size: 1.6rem;
    line-height: 1.35;
    color: var(--nl-text);
}

.nl-join .nl-promo-text {
    color: #5c5c5c;
}

.nl-join .nl-small {
    margin-top: 1.25rem;
}

/* ---------- FAQ ---------- */
.nl-faq {
    display: grid;
    grid-template-columns: 0.9fr 1.35fr;
    gap: 2.5rem;
    align-items: start;
}

.nl-faq-aside h3 {
    margin: 1.25rem 0 1.75rem;
    font-family: 'Hanken Grotesk', 'Open Sans', sans-serif;
    font-weight: 700;
    font-size: 2.2rem;
    line-height: 1.25;
}

.nl-faq-aside p {
    margin: 0 0 2.5rem;
    font-size: 1.5rem;
    line-height: 1.4;
}

.nl-faq-art {
    margin-top: 4.5rem;
    aspect-ratio: 1;
    max-width: 420px;
    border-radius: 50%;
    background: var(--nl-badge);
    display: flex;
    align-items: center;
    justify-content: center;
}

.nl-faq-art img {
    width: 112%;
    max-width: none;
}

.nl-faq-list {
    background: var(--nl-gray);
    border-radius: 36px;
    padding: 2.5rem 1.5rem 1.5rem;
}

.nl-faq-item + .nl-faq-item {
    margin-top: 2.5rem;
}

.nl-faq-item h3 {
    margin: 0 0 0.75rem;
    font-weight: 700;
    font-size: 1.5rem;
}

.nl-faq-item p {
    margin: 0;
    font-size: 1.5rem;
    line-height: 1.4;
}

/* ---------- Contacto ---------- */
.nl-contact {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
}

.nl-contact-intro {
    margin: 0 0 2.5rem;
    max-width: 440px;
    font-size: 1.45rem;
    line-height: 1.4;
}

.nl-contact-item {
    display: flex;
    gap: 2rem;
}

.nl-contact-item + .nl-contact-item {
    margin-top: 3.5rem;
}

.nl-contact-icon {
    width: 92px;
    height: 92px;
    flex-shrink: 0;
    border-radius: 50%;
    color: #ffffff;
    font-size: 2.8rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.nl-contact-item h3 {
    margin: 0;
    font-weight: 700;
    font-size: 1.5rem;
}

.nl-contact-item p {
    margin: 0.25rem 0 1.25rem;
    font-size: 1.5rem;
    line-height: 1.4;
}

.nl-contact-btn {
    min-width: 330px;
    padding: 0.7rem 1.5rem;
    border-radius: 16px;
    color: #ffffff;
    font-size: 1.5rem;
}

.nl-contact-btn:hover {
    color: #ffffff;
    filter: brightness(1.05);
}

.nl-contact-btn i {
    font-size: 1.7rem;
}

.nl-contact-item .nl-contact-note {
    margin: 1rem 0 0 1rem;
    font-size: 0.95rem;
    line-height: 1.5;
}

.nl-contact-visual {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 3.5rem;
}

.nl-contact-phone-wrap {
    position: relative;
    width: 100%;
    max-width: 560px;
}

.nl-contact-phone-wrap::before {
    content: '';
    position: absolute;
    right: 0;
    top: 12%;
    width: 86%;
    aspect-ratio: 1;
    border-radius: 50%;
    background: #d6c7fb;
}

.nl-contact-phone {
    position: relative;
    display: block;
    width: 100%;
}

.nl-banner {
    display: grid;
    grid-template-columns: auto 1fr;
    align-items: center;
    gap: 2rem;
    margin-top: 6rem;
    padding: 3.25rem 2rem;
    border-radius: 44px;
    background: #d8c8fa;
}

.nl-banner-photo {
    width: 280px;
    border-radius: 18px;
}

.nl-banner-copy {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}

.nl-banner-copy h3 {
    margin: 0;
    font-family: 'Hanken Grotesk', 'Open Sans', sans-serif;
    font-weight: 700;
    font-size: 1.5rem;
}

.nl-banner-copy p {
    margin: 0.25rem 0 1.75rem;
    font-weight: 700;
    font-size: 1.5rem;
    color: #6a1fb5;
}

/* ---------- Footer ---------- */
.nl-footer {
    background: #001a3f;
    color: #ffffff;
    padding: 3rem 0 2rem;
}

.nl-footer-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 2rem;
}

.nl-footer-brand {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.nl-footer-brand img {
    width: 58px;
    height: 58px;
}

.nl-footer-brand span {
    font-family: 'Rammetto One', 'Hanken Grotesk', sans-serif;
    font-size: 2.4rem;
}

.nl-footer-top p {
    margin: 0.75rem 0 0 0.25rem;
    font-family: 'Hanken Grotesk', 'Open Sans', sans-serif;
    font-size: 1.3rem;
}

.nl-footer-social {
    display: flex;
    align-items: center;
    gap: 1.25rem;
    font-family: 'Hanken Grotesk', 'Open Sans', sans-serif;
    font-size: 1.3rem;
}

.nl-footer-social span {
    margin-right: 1rem;
}

.nl-footer-social a {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    background: #ffffff;
    color: #001a3f;
    font-size: 2.1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: all 0.2s ease;
}

.nl-footer-social a:hover {
    transform: translateY(-2px);
    color: #6a1fb5;
}

.nl-footer-bottom {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 0.75rem 3rem;
    margin-top: 5rem;
    font-family: 'Hanken Grotesk', 'Open Sans', sans-serif;
    font-size: 1.15rem;
}

.nl-footer-bottom a {
    color: #38b6ff;
}

.nl-footer-bottom a:hover {
    color: #7dd0ff;
}

/* ---------- Tablet ---------- */
@media (max-width: 1024px) {
    .nl-plan {
        padding: 3rem 2rem;
    }

    .nl-plan-amount {
        font-size: 5.5rem;
    }

    .nl-plan-name,
    .nl-plan-before,
    .nl-plan-today {
        margin-left: 0;
    }

    .nl-testimonials {
        gap: 1.5rem;
    }

    .nl-promo,
    .nl-join {
        padding: 2rem;
    }
}

/* ---------- Mobile ---------- */
@media (max-width: 860px) {
    .nl-section {
        padding: 3.5rem 0;
    }

    .nl-head {
        margin-bottom: 2rem;
    }

    .nl-badge {
        font-size: 1rem;
    }

    .nl-badge img {
        width: 28px;
        height: 28px;
    }

    .nl-title {
        font-size: 1.9rem;
    }

    .nl-sub {
        font-size: 1.1rem;
    }

    .nl-brand-mark {
        width: 72px;
        height: 72px;
    }

    .nl-brand-name {
        font-size: 2.1rem;
    }

    .nl-brand-tag {
        font-size: 0.7rem;
    }

    .nl-hero-grid,
    .nl-plan,
    .nl-promo,
    .nl-join,
    .nl-faq,
    .nl-contact,
    .nl-banner,
    .nl-testimonials {
        grid-template-columns: 1fr;
    }

    .nl-hero-visual {
        order: -1;
    }

    .nl-hero-phone {
        max-width: 420px;
        margin: 1rem auto 0;
    }

    .nl-hero-title {
        font-size: 1.75rem;
    }

    .nl-hero-list {
        margin-top: 1.5rem;
        font-size: 1.2rem;
    }

    .nl-hero-list li + li {
        margin-top: 1rem;
    }

    .nl-hero-actions {
        margin: 2.5rem 0;
    }

    .nl-hero-actions .nl-btn-ghost {
        margin-top: 1.5rem;
    }

    .nl-btn-white,
    .nl-btn-ghost {
        min-height: 68px;
        font-size: 1.5rem;
    }

    .nl-features {
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem;
    }

    .nl-feature {
        border-radius: 32px;
        min-height: 0;
    }

    .nl-feature-icon {
        font-size: 2.8rem;
        height: 60px;
    }

    .nl-steps {
        flex-direction: column;
        gap: 3.5rem;
    }

    .nl-step {
        flex: none;
        width: 100%;
        max-width: 340px;
        min-height: 0;
    }

    .nl-step-arrow {
        display: none;
    }

    .nl-btn-start {
        font-size: 1.3rem;
        padding: 1rem 1.75rem;
    }

    .nl-btn-gradient {
        font-size: 1.2rem;
    }

    .nl-btn-lg {
        padding: 1.5rem 1rem;
    }

    .nl-btn-xl {
        padding: 1.5rem 1rem;
        font-size: 1.6rem;
    }

    .nl-testimonial-text {
        font-size: 1.15rem;
    }

    .nl-testimonial-author img {
        width: 84px;
        height: 84px;
    }

    .nl-testimonial-author strong {
        font-size: 1.25rem;
    }

    .nl-trust {
        margin: 3rem 0;
        gap: 1rem;
    }

    .nl-trust p {
        font-size: 1.15rem;
    }

    .nl-trust-icon {
        width: 76px;
        height: 76px;
        font-size: 2.3rem;
    }

    .nl-trust-card {
        margin-bottom: 0;
        padding: 1.25rem;
        border-radius: 32px;
    }

    .nl-plan {
        padding: 2rem 1.25rem;
        border-radius: 32px;
    }

    .nl-plan-price {
        align-items: center;
    }

    .nl-plan-name,
    .nl-plan-before,
    .nl-plan-today,
    .nl-plan-includes h3 {
        font-size: 1.7rem;
    }

    .nl-plan-name {
        margin-bottom: 1rem;
    }

    .nl-plan-today {
        align-self: flex-start;
    }

    .nl-plan-amount {
        font-size: 3.9rem;
    }

    .nl-plan-currency {
        font-size: 2.4rem;
        vertical-align: 0.8rem;
    }

    .nl-plan-period {
        font-size: 1.5rem;
    }

    .nl-plan-after {
        margin-bottom: 1.25rem;
        font-size: 0.9rem;
    }

    .nl-plan-save {
        margin-bottom: 2rem;
        padding: 0.75rem 1.25rem;
        font-size: 1.4rem;
    }

    .nl-plan-perks {
        margin-top: 2rem;
        align-self: stretch;
        font-size: 1.2rem;
    }

    .nl-plan-perks li + li {
        margin-top: 1.25rem;
    }

    .nl-plan-perks i {
        width: 44px;
        font-size: 2.1rem;
    }

    .nl-plan-includes ul {
        font-size: 1.2rem;
    }

    .nl-plan-icon {
        width: 56px;
        height: 56px;
        font-size: 1.5rem;
    }

    .nl-promo,
    .nl-join {
        padding: 2rem 1.25rem;
        border-radius: 32px;
    }

    .nl-join {
        margin-top: 3.5rem;
    }

    .nl-promo-phone,
    .nl-join-phone {
        max-width: 220px;
    }

    .nl-promo-copy,
    .nl-join-copy {
        align-items: center;
        text-align: center;
    }

    .nl-title-left {
        text-align: center;
    }

    .nl-promo-text {
        font-size: 1.2rem;
        margin: 1rem 0 1.75rem;
    }

    .nl-faq-aside {
        text-align: center;
    }

    .nl-faq-aside h3 {
        font-size: 1.6rem;
        margin-top: 0;
    }

    .nl-faq-aside p,
    .nl-faq-item h3,
    .nl-faq-item p {
        font-size: 1.1rem;
    }

    .nl-btn-outline-purple {
        font-size: 1.15rem;
        padding: 0.75rem 1.5rem;
        border-width: 4px;
    }

    .nl-btn-outline-purple i {
        font-size: 1.8rem;
    }

    .nl-faq-art {
        display: none;
    }

    .nl-faq-list {
        padding: 1.75rem 1.25rem 1.25rem;
        border-radius: 28px;
    }

    .nl-faq-item + .nl-faq-item {
        margin-top: 1.5rem;
    }

    .nl-contact-intro,
    .nl-contact-item p {
        font-size: 1.1rem;
    }

    .nl-contact-item {
        gap: 1rem;
    }

    .nl-contact-item + .nl-contact-item {
        margin-top: 2.25rem;
    }

    .nl-contact-item h3 {
        font-size: 1.15rem;
    }

    .nl-contact-icon {
        width: 58px;
        height: 58px;
        font-size: 1.8rem;
    }

    .nl-contact-btn {
        min-width: 0;
        width: 100%;
        font-size: 1.1rem;
    }

    .nl-contact-visual {
        gap: 2rem;
        margin-top: 1rem;
    }

    .nl-contact-phone-wrap {
        max-width: 340px;
    }

    .nl-banner {
        margin-top: 3.5rem;
        padding: 1.5rem 1.25rem 2rem;
        border-radius: 32px;
        justify-items: center;
    }

    .nl-banner-photo {
        width: 100%;
        max-width: 320px;
    }

    .nl-banner-copy h3,
    .nl-banner-copy p {
        font-size: 1.15rem;
    }

    .nl-footer-brand span {
        font-size: 1.8rem;
    }

    .nl-footer-top p,
    .nl-footer-social {
        font-size: 1.1rem;
    }

    .nl-footer-social a {
        width: 50px;
        height: 50px;
        font-size: 1.6rem;
    }

    .nl-footer-bottom {
        margin-top: 2.5rem;
        font-size: 0.95rem;
        text-align: center;
    }
}
</style>

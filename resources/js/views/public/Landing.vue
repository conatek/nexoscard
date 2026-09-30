<template>
    <div class="lt-page" :class="{ 'lt-motion': motionOk }">
        <!-- Progreso de lectura -->
        <div class="lt-progress" :style="{ transform: `scaleX(${scrollProgress})` }" aria-hidden="true"></div>

        <!-- ===================== Navegación flotante ===================== -->
        <nav class="lt-nav" :class="{ 'is-scrolled': scrolled }" aria-label="Secciones">
            <router-link to="/inicio" class="lt-nav-brand" aria-label="Nexos Card">
                <img :src="img('logo-mark')" alt="">
                <span>NEXOS Card</span>
            </router-link>
            <div class="lt-nav-links">
                <a v-for="l in navLinks" :key="l.id" :href="`#${l.id}`" @click.prevent="goTo(l.id)">{{ l.label }}</a>
            </div>
            <div class="lt-nav-actions">
                <router-link v-if="isAuthenticated" to="/" class="lt-btn lt-btn-glass lt-btn-sm">Mi panel</router-link>
                <router-link v-else to="/login" class="lt-btn lt-btn-glass lt-btn-sm">Iniciar Sesión</router-link>
                <router-link to="/register" class="lt-btn lt-btn-primary lt-btn-sm">Crear Mi Tarjeta</router-link>
            </div>
        </nav>

        <!-- ===================== Hero ===================== -->
        <header class="lt-hero" @pointermove="onHeroPointer" @pointerleave="resetTilt">
            <div class="lt-aurora" aria-hidden="true">
                <span class="lt-blob lt-blob-1"></span>
                <span class="lt-blob lt-blob-2"></span>
                <span class="lt-blob lt-blob-3"></span>
            </div>
            <div class="lt-grid-lines" aria-hidden="true"></div>

            <div class="lt-container lt-hero-inner">
                <div class="lt-hero-copy">
                    <span class="lt-eyebrow lt-intro" style="--i: 0">
                        <span class="lt-pulse"></span> Tarjetas de contacto interactivas
                    </span>

                    <h1 class="lt-hero-title">
                        <span
                            v-for="(w, i) in heroWords"
                            :key="i"
                            class="lt-word"
                            :class="{ 'lt-gradient-text': i >= heroHighlightFrom }"
                            :style="{ '--i': i + 1 }"
                        >{{ w }}&nbsp;</span>
                    </h1>

                    <ul class="lt-hero-list">
                        <li v-for="(b, i) in hero.bullets" :key="b" class="lt-intro" :style="{ '--i': i + 10 }">
                            <span class="lt-check"><i class="fas fa-check" aria-hidden="true"></i></span>{{ b }}
                        </li>
                    </ul>

                    <div class="lt-hero-ctas lt-intro" style="--i: 13">
                        <router-link to="/register" class="lt-btn lt-btn-primary lt-btn-lg">
                            Crear Mi Tarjeta <i class="fas fa-arrow-right" aria-hidden="true"></i>
                        </router-link>
                        <router-link :to="demoUrl" class="lt-btn lt-btn-glass lt-btn-lg">
                            <span class="lt-play"><i class="fas fa-play" aria-hidden="true"></i></span> Ver Demo en Vivo
                        </router-link>
                    </div>

                    <p class="lt-hero-note lt-intro" style="--i: 14">
                        <span>*Pruébala <strong>GRATIS</strong>. No necesitas tarjeta de crédito.</span>
                        <a :href="guideUrl" target="_blank" rel="noopener" class="lt-link">
                            <i class="far fa-file-pdf" aria-hidden="true"></i> Guía rápida PDF paso a paso
                        </a>
                    </p>
                </div>

                <div class="lt-hero-visual lt-intro" style="--i: 6">
                    <div class="lt-phone-stage" :style="tiltStyle">
                        <span class="lt-halo" aria-hidden="true"></span>
                        <img :src="img('hero-phone')" alt="Tarjeta digital Nexos Card en un celular" class="lt-hero-phone">

                        <span class="lt-chip lt-chip-1"><i class="fab fa-whatsapp" aria-hidden="true"></i> WhatsApp directo</span>
                        <span class="lt-chip lt-chip-2"><i class="fas fa-qrcode" aria-hidden="true"></i> Código QR</span>
                        <span class="lt-chip lt-chip-3"><i class="fas fa-map-marker-alt" aria-hidden="true"></i> Google Maps</span>
                        <span class="lt-chip lt-chip-4"><i class="fas fa-sync-alt" aria-hidden="true"></i> Sin reimprimir</span>
                    </div>
                </div>
            </div>

            <button type="button" class="lt-scroll-cue" aria-label="Ver más" @click="goTo('que-incluye')">
                <span></span>
            </button>
        </header>

        <!-- ===================== Cinta de funciones ===================== -->
        <div class="lt-marquee" aria-hidden="true">
            <div class="lt-marquee-track">
                <span v-for="(f, i) in marqueeItems" :key="i" class="lt-marquee-item">
                    <i :class="f.icon"></i> {{ f.title }}
                </span>
            </div>
        </div>

        <!-- ===================== Todo lo que incluye ===================== -->
        <section id="que-incluye" class="lt-section">
            <div class="lt-container">
                <header class="lt-head" v-reveal>
                    <span class="lt-eyebrow">Todo lo que incluye</span>
                    <h2 class="lt-title">Todo lo que necesitas para <span class="lt-gradient-text">destacar tu negocio</span></h2>
                    <p class="lt-sub">Herramientas poderosas para mostrar tu negocio de forma profesional y conectar con más clientes</p>
                </header>

                <div class="lt-bento">
                    <article
                        v-for="(f, i) in features"
                        :key="f.title"
                        v-reveal="(i % 4) * 80"
                        class="lt-card lt-spot"
                        :class="{ 'lt-card-wide': wideFeatures.includes(i) }"
                        @pointermove="spotlight"
                    >
                        <span class="lt-icon-tile"><i :class="f.icon" aria-hidden="true"></i></span>
                        <div>
                            <h3>{{ f.title }}</h3>
                            <p>{{ f.text }}</p>
                        </div>
                    </article>
                </div>
            </div>
        </section>

        <!-- ===================== Proceso simple ===================== -->
        <section id="como-funciona" class="lt-section lt-section-alt">
            <div class="lt-container">
                <header class="lt-head" v-reveal>
                    <span class="lt-eyebrow">Proceso simple</span>
                    <h2 class="lt-title">¿Cómo funciona <span class="lt-gradient-text">Nexos Card?</span></h2>
                    <p class="lt-sub">Crear tu tarjeta digital es rápido, sencillo y no necesitas conocimientos técnicos</p>
                </header>

                <div class="lt-steps" v-reveal>
                    <span class="lt-steps-line" aria-hidden="true"></span>
                    <article v-for="(s, i) in steps" :key="s.title" class="lt-step" :style="{ '--i': i }">
                        <span class="lt-step-orb">
                            <i :class="s.icon" aria-hidden="true"></i>
                        </span>
                        <span class="lt-step-num">0{{ i + 1 }}</span>
                        <h3>{{ s.title }}</h3>
                        <p>{{ s.text }}</p>
                    </article>
                </div>

                <div class="lt-center" v-reveal>
                    <router-link to="/register" class="lt-btn lt-btn-primary lt-btn-lg">
                        Empieza gratis <i class="fas fa-arrow-right" aria-hidden="true"></i>
                    </router-link>
                    <p class="lt-fine"><i class="fas fa-shield-alt" aria-hidden="true"></i> Sin tarjeta de crédito - Cancela cuando quieras</p>
                    <p class="lt-fine">✓ Incluye guía paso a paso para configurar tu tarjeta en minutos.</p>
                </div>
            </div>
        </section>

        <!-- ===================== Cifras ===================== -->
        <section class="lt-stats-band" ref="stats">
            <div class="lt-container">
                <p class="lt-stats-tagline" v-reveal>Impulsando las ventas de negocios independientes y profesionales</p>
                <div class="lt-stats">
                    <div v-for="(s, i) in stats" :key="s.label" class="lt-stat" v-reveal="i * 90">
                        <strong class="lt-gradient-text">{{ s.prefix || '' }}{{ s.static || Math.round(s.value * statsProgress) }}{{ s.suffix || '' }}</strong>
                        <span>{{ s.label }}</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- ===================== Testimonios ===================== -->
        <section id="testimonios" class="lt-section">
            <div class="lt-container">
                <header class="lt-head" v-reveal>
                    <span class="lt-eyebrow">Testimonios reales</span>
                    <h2 class="lt-title">Lo que dicen <span class="lt-gradient-text">nuestros clientes</span></h2>
                    <p class="lt-sub">Profesionales y emprendedores ya están creciendo con su tarjeta digital Nexos Card</p>
                </header>

                <div class="lt-testimonials">
                    <figure v-for="(t, i) in testimonials" :key="t.name" v-reveal="i * 120" class="lt-card lt-quote-card lt-spot" @pointermove="spotlight">
                        <div class="lt-quote-top">
                            <span class="lt-quote-mark" aria-hidden="true">“</span>
                            <span class="lt-stars" aria-label="5 estrellas">
                                <i v-for="n in 5" :key="n" class="fas fa-star" aria-hidden="true"></i>
                            </span>
                        </div>
                        <blockquote>{{ t.quote }}</blockquote>
                        <figcaption>
                            <span class="lt-avatar"><img :src="img(t.logo)" :alt="t.company"></span>
                            <span class="lt-author">
                                <strong>{{ t.name }}</strong>
                                <span>{{ t.role }} · {{ t.company }}</span>
                                <span class="lt-place"><i class="fas fa-map-marker-alt" aria-hidden="true"></i> {{ t.place }}</span>
                            </span>
                        </figcaption>
                    </figure>
                </div>
            </div>
        </section>

        <!-- ===================== Plan único ===================== -->
        <section id="precio" class="lt-section lt-section-alt lt-pricing-section">
            <div class="lt-glow lt-glow-center" aria-hidden="true"></div>
            <div class="lt-container">
                <header class="lt-head" v-reveal>
                    <span class="lt-eyebrow">Plan único</span>
                    <h2 class="lt-title">Empieza hoy con <span class="lt-gradient-text">{{ pricing.name }}</span></h2>
                    <p class="lt-sub">Todo lo que necesitas para tu presencia profesional en un solo pago simple</p>
                </header>

                <div class="lt-price-card" v-reveal>
                    <div class="lt-price-inner">
                        <div class="lt-price-main">
                            <span class="lt-plan-tag">{{ pricing.name.toUpperCase() }}</span>
                            <p v-if="pricing.hasOffer" class="lt-price-before">Antes <s>${{ money(pricing.regular) }}</s></p>
                            <p class="lt-price-today">Hoy</p>
                            <p class="lt-price">
                                <span class="lt-price-currency">$</span><span class="lt-gradient-text">{{ money(pricing.price) }}</span><span class="lt-price-period">/año</span>
                            </p>
                            <p class="lt-price-after">(después de tu prueba)</p>
                            <span v-if="pricing.hasOffer" class="lt-save">
                                <i class="fas fa-tag" aria-hidden="true"></i>
                                Ahorras ${{ money(pricing.regular - pricing.price) }} ({{ pricing.discount }}%)
                            </span>

                            <router-link to="/register" class="lt-btn lt-btn-primary lt-btn-lg lt-btn-block">
                                Probar {{ trialDays }} Días Gratis <i class="fas fa-arrow-right" aria-hidden="true"></i>
                            </router-link>

                            <ul class="lt-perks">
                                <li v-for="p in planPerks" :key="p.text"><i :class="p.icon" aria-hidden="true"></i> {{ p.text }}</li>
                            </ul>
                        </div>

                        <div class="lt-price-includes">
                            <h3>Todo lo que incluye tu plan</h3>
                            <ul>
                                <li v-for="(item, i) in planItems" :key="item.text" v-reveal="i * 60">
                                    <span class="lt-inc-icon"><i :class="item.icon" aria-hidden="true"></i></span>
                                    {{ item.text }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <p class="lt-trust" v-reveal>
                    <i class="fas fa-shield-alt" aria-hidden="true"></i>
                    Más de {{ clientsCount }} negocios y empresas ya confían en Nexos Card
                </p>

                <div class="lt-promo" ref="promo">
                    <div class="lt-promo-visual" v-reveal>
                        <span class="lt-ring" aria-hidden="true"></span>
                        <img :src="img('phone-promo')" alt="Ejemplo de tarjeta Nexos Card" class="lt-promo-phone" :style="{ transform: `translateY(${promoShift}px) rotate(-8deg)` }">
                    </div>
                    <div class="lt-promo-copy" v-reveal="120">
                        <h2 class="lt-title lt-left">Tu tarjeta digital <span class="lt-gradient-text">trabaja por ti 24/7</span></h2>
                        <p class="lt-lead">Comparte tu información en segundos, genera confianza y recibe más clientes</p>
                        <router-link to="/register" class="lt-btn lt-btn-primary lt-btn-lg">
                            Comenzar Prueba Gratis <i class="fas fa-arrow-right" aria-hidden="true"></i>
                        </router-link>
                    </div>
                </div>
            </div>
        </section>

        <!-- ===================== Preguntas frecuentes ===================== -->
        <section id="preguntas" class="lt-section">
            <div class="lt-container lt-faq">
                <aside class="lt-faq-aside" v-reveal>
                    <span class="lt-eyebrow">Preguntas frecuentes</span>
                    <h2 class="lt-title lt-left">Resuelve <span class="lt-gradient-text">tus dudas</span></h2>
                    <p class="lt-lead">Todo lo que necesitas saber sobre tu tarjeta digital Nexos Card</p>
                    <img :src="img('faq-bubbles')" alt="" class="lt-faq-art">
                    <div class="lt-card lt-faq-help">
                        <h3>¿Tienes otra pregunta?</h3>
                        <p>Estamos aquí para ayudarte. Escríbenos por WhatsApp y te responderemos con gusto.</p>
                        <a :href="whatsappUrl" target="_blank" rel="noopener" class="lt-btn lt-btn-whatsapp">
                            <i class="fab fa-whatsapp" aria-hidden="true"></i> Escríbenos por WhatsApp
                        </a>
                    </div>
                </aside>

                <div class="lt-accordion" v-reveal="120">
                    <div v-for="(q, i) in faqs" :key="q.q" class="lt-acc-item" :class="{ 'is-open': openFaq === i }">
                        <button type="button" class="lt-acc-head" :aria-expanded="openFaq === i" @click="openFaq = openFaq === i ? null : i">
                            <span>{{ q.q }}</span>
                            <span class="lt-acc-sign" aria-hidden="true"></span>
                        </button>
                        <div class="lt-acc-body">
                            <div><p>{{ q.a }}</p></div>
                        </div>
                    </div>
                    <a :href="guideUrl" target="_blank" rel="noopener" class="lt-link lt-acc-guide">
                        <i class="far fa-file-pdf" aria-hidden="true"></i> Guía rápida PDF paso a paso
                    </a>
                </div>
            </div>
        </section>

        <!-- ===================== Únete ===================== -->
        <section class="lt-section lt-section-tight">
            <div class="lt-container">
                <div class="lt-join" v-reveal>
                    <div class="lt-join-mesh" aria-hidden="true"></div>
                    <img :src="img('phone-advisor')" alt="Ejemplo de tarjeta Nexos Card" class="lt-join-phone">
                    <div class="lt-join-copy">
                        <h2 class="lt-title lt-left">Únete a los profesionales y negocios que ya usan Nexos Card</h2>
                        <p class="lt-lead">Crea tu tarjeta digital hoy y empieza a conectar más y mejor.</p>
                        <router-link to="/register" class="lt-btn lt-btn-light lt-btn-lg">
                            Comenzar Prueba Gratis <i class="fas fa-arrow-right" aria-hidden="true"></i>
                        </router-link>
                        <p class="lt-fine lt-fine-light">
                            <i class="fas fa-shield-alt" aria-hidden="true"></i> Prueba gratis por {{ trialDays }} días • Sin tarjeta de crédito
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ===================== Contacto ===================== -->
        <section id="contacto" class="lt-section lt-section-alt">
            <div class="lt-container">
                <header class="lt-head" v-reveal>
                    <span class="lt-eyebrow">Contacto</span>
                    <h2 class="lt-title">Estamos aquí <span class="lt-gradient-text">para ayudarte</span></h2>
                    <p class="lt-sub">Nuestro equipo está listo para resolver todas tus dudas y ayudarte a crear tu tarjeta digital perfecta.</p>
                </header>

                <div class="lt-contact">
                    <div class="lt-contact-grid">
                        <article
                            v-for="(c, i) in contactItems"
                            :key="c.key"
                            v-reveal="(i % 2) * 100"
                            class="lt-card lt-contact-card lt-spot"
                            :class="`lt-c-${c.key}`"
                            @pointermove="spotlight"
                        >
                            <span class="lt-contact-icon"><i :class="c.icon" aria-hidden="true"></i></span>
                            <h3>{{ c.title }}</h3>
                            <p>{{ c.text }}</p>
                            <router-link v-if="c.to" :to="c.to" class="lt-contact-link">
                                {{ c.label }} <i class="fas fa-arrow-right" aria-hidden="true"></i>
                            </router-link>
                            <a v-else :href="c.href" target="_blank" rel="noopener" class="lt-contact-link">
                                {{ c.label }} <i class="fas fa-arrow-right" aria-hidden="true"></i>
                            </a>
                            <p v-if="c.note" class="lt-contact-note"><strong>Importante:</strong> {{ c.note }}</p>
                        </article>
                    </div>

                    <div class="lt-contact-visual" v-reveal="150">
                        <span class="lt-ring lt-ring-soft" aria-hidden="true"></span>
                        <img :src="img('phone-contact')" alt="Ejemplo de tarjeta Nexos Card" class="lt-contact-phone">
                        <router-link to="/register" class="lt-btn lt-btn-primary lt-btn-lg">
                            Empieza Gratis <i class="fas fa-arrow-right" aria-hidden="true"></i>
                        </router-link>
                    </div>
                </div>

                <div class="lt-banner" v-reveal>
                    <img :src="img('professionals')" alt="Profesionales de distintos rubros" class="lt-banner-photo">
                    <div class="lt-banner-copy">
                        <h3>Muchos profesionales y negocios ya están conectados más y mejor</h3>
                        <p class="lt-gradient-text">Únete a ellos y lleva tu negocio al siguiente nivel</p>
                    </div>
                    <router-link to="/register" class="lt-btn lt-btn-primary lt-btn-lg">
                        Comenzar Prueba Gratis <i class="fas fa-arrow-right" aria-hidden="true"></i>
                    </router-link>
                </div>
            </div>
        </section>

        <!-- ===================== Footer ===================== -->
        <footer class="lt-footer">
            <div class="lt-container">
                <div class="lt-footer-top">
                    <div>
                        <div class="lt-footer-brand">
                            <img :src="img('logo-mark')" alt="">
                            <span>Nexos Card</span>
                        </div>
                        <p>La tarjeta digital que conecta tu negocio.</p>
                    </div>
                    <div class="lt-footer-social">
                        <span>Síguenos</span>
                        <a v-for="s in socials" :key="s.label" :href="s.href" target="_blank" rel="noopener" :aria-label="s.label">
                            <i :class="s.icon" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
                <div class="lt-footer-bottom">
                    <span>© {{ year }} Nexos Card - Todos los derechos reservados.</span>
                    <span class="lt-footer-legal">
                        <router-link to="/politica-privacidad">Política de Privacidad</router-link>
                        <router-link to="/terminos-condiciones">Términos y Condiciones</router-link>
                    </span>
                </div>
            </div>
            <div class="lt-footer-word" aria-hidden="true">NEXOS</div>
        </footer>
    </div>
</template>

<script>
import api from '@/services/api.js';
import { useAuth } from '@/stores/auth';
import {
    FALLBACK, DEMO_URL, CLIENTS_COUNT, hero, features, steps, testimonials, planPerks, planItems,
    socials, money, landingImage, buildFaqs, buildContactItems, fetchLandingData, defaultPricing,
    loadFonts,
} from './landing/content.js';

const HIGHLIGHT_WORDS = 4; // "en un solo enlace" va con degradado

export default {
    name: 'Landing',

    directives: {
        // Aparición al entrar en pantalla. El valor opcional es un retraso en ms para
        // escalonar elementos de una misma fila.
        reveal: {
            mounted(el, binding) {
                el.classList.add('lt-reveal');
                if (binding.value) el.style.setProperty('--d', `${binding.value}ms`);
                binding.instance.observeReveal(el);
            },
        },
    },

    data() {
        return {
            hero,
            features,
            steps,
            testimonials,
            planPerks,
            planItems,
            socials,
            demoUrl:      DEMO_URL,
            clientsCount: CLIENTS_COUNT,
            pricing:      defaultPricing(),
            trialDays:    FALLBACK.trialDays,
            whatsapp:     FALLBACK.whatsapp,
            email:        FALLBACK.email,
            guideUrl:     FALLBACK.guideUrl,

            // Tarjetas que ocupan dos columnas en el bento: QR, WhatsApp, Video y Hosting.
            wideFeatures: [0, 1, 6, 7],
            navLinks: [
                { id: 'que-incluye',  label: 'Qué incluye' },
                { id: 'como-funciona', label: 'Cómo funciona' },
                { id: 'testimonios',  label: 'Testimonios' },
                { id: 'precio',       label: 'Precio' },
                { id: 'preguntas',    label: 'Preguntas' },
                { id: 'contacto',     label: 'Contacto' },
            ],

            openFaq:        0,
            motionOk:       true,
            scrolled:       false,
            scrollProgress: 0,
            statsProgress:  0,
            promoShift:     0,
            tilt:           { x: 0, y: 0 },
        };
    },

    computed: {
        year() {
            return new Date().getFullYear();
        },
        // Quien ya tiene sesión ve "Mi panel" en lugar de "Iniciar Sesión".
        isAuthenticated() {
            return useAuth().isAuthenticated.value;
        },
        heroWords() {
            return this.hero.title.split(' ');
        },
        heroHighlightFrom() {
            return this.heroWords.length - HIGHLIGHT_WORDS;
        },
        marqueeItems() {
            // Duplicada para que el desplazamiento infinito no tenga salto.
            return [...this.features, ...this.features];
        },
        whatsappUrl() {
            return `https://wa.me/${this.whatsapp}`;
        },
        faqs() {
            return buildFaqs({ planName: this.pricing.name, price: this.pricing.price, trialDays: this.trialDays });
        },
        contactItems() {
            return buildContactItems({ whatsappUrl: this.whatsappUrl, email: this.email, guideUrl: this.guideUrl });
        },
        stats() {
            return [
                { value: this.clientsCount, prefix: '+', label: 'negocios y empresas ya confían en Nexos Card' },
                { value: this.trialDays, label: 'días de prueba gratis, sin tarjeta de crédito' },
                this.pricing.hasOffer
                    ? { value: this.pricing.discount, suffix: '%', label: 'de ahorro en tu plan anual' }
                    : { value: 100, suffix: '%', label: 'responsive en cualquier pantalla' },
                { static: '24/7', label: 'tu tarjeta trabaja por ti' },
            ];
        },
        tiltStyle() {
            return { transform: `perspective(1200px) rotateX(${this.tilt.y}deg) rotateY(${this.tilt.x}deg)` };
        },
    },

    created() {
        this.motionOk = !window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        this.finePointer = window.matchMedia('(pointer: fine)').matches;
        this.frame = null;
        this.pendingReveal = new Set();

        this.revealObserver = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) this.reveal(entry.target);
            });
        }, { threshold: 0.15, rootMargin: '0px 0px -40px 0px' });
    },

    async mounted() {
        document.title = 'NEXOS Card | Tu tarjeta digital profesional';
        loadFonts('lt-landing-fonts', 'https://fonts.googleapis.com/css2?family=Rammetto+One&display=swap');

        window.addEventListener('scroll', this.onScroll, { passive: true });
        this.onScroll();
        this.watchStats();

        const data = await fetchLandingData(api);
        if (data) {
            if (data.pricing) this.pricing = data.pricing;
            this.trialDays = data.trialDays;
            this.whatsapp  = data.whatsapp;
            this.email     = data.email;
            this.guideUrl  = data.guideUrl;
        }
    },

    beforeUnmount() {
        window.removeEventListener('scroll', this.onScroll);
        this.revealObserver.disconnect();
        this.statsObserver?.disconnect();
        cancelAnimationFrame(this.frame);
    },

    methods: {
        img: landingImage,
        money,

        observeReveal(el) {
            if (!this.motionOk) {
                el.classList.add('is-in');
                return;
            }
            this.pendingReveal.add(el);
            this.revealObserver.observe(el);
        },

        reveal(el) {
            el.classList.add('is-in');
            this.pendingReveal.delete(el);
            this.revealObserver.unobserve(el);
        },

        // Con un scroll muy rápido un elemento pequeño puede cruzar la pantalla sin que el
        // observer lo reporte: todo lo que ya quedó por encima del borde inferior se muestra.
        revealPassed() {
            const limit = window.innerHeight;
            const passed = [...this.pendingReveal].filter((el) => el.getBoundingClientRect().top < limit);
            passed.forEach((el) => this.reveal(el));
        },

        goTo(id) {
            document.getElementById(id)?.scrollIntoView({ behavior: this.motionOk ? 'smooth' : 'auto' });
        },

        // Un solo requestAnimationFrame por fotograma para barra de progreso, nav y parallax.
        onScroll() {
            if (this.frame) return;
            this.frame = requestAnimationFrame(() => {
                this.frame = null;
                const max = document.documentElement.scrollHeight - window.innerHeight;
                this.scrollProgress = max > 0 ? Math.min(window.scrollY / max, 1) : 0;
                this.scrolled = window.scrollY > 24;
                if (this.pendingReveal.size) this.revealPassed();

                if (this.motionOk && this.$refs.promo) {
                    const r = this.$refs.promo.getBoundingClientRect();
                    const center = r.top + r.height / 2 - window.innerHeight / 2;
                    this.promoShift = Math.max(-40, Math.min(40, center * -0.12));
                }
            });
        },

        onHeroPointer(e) {
            if (!this.motionOk || !this.finePointer) return;
            const r = e.currentTarget.getBoundingClientRect();
            const px = (e.clientX - r.left) / r.width - 0.5;
            const py = (e.clientY - r.top) / r.height - 0.5;
            this.tilt = { x: px * 14, y: py * -10 };
        },

        resetTilt() {
            this.tilt = { x: 0, y: 0 };
        },

        // Luz que sigue al puntero dentro de la tarjeta.
        spotlight(e) {
            const el = e.currentTarget;
            const r = el.getBoundingClientRect();
            el.style.setProperty('--mx', `${e.clientX - r.left}px`);
            el.style.setProperty('--my', `${e.clientY - r.top}px`);
        },

        watchStats() {
            if (!this.motionOk) {
                this.statsProgress = 1;
                return;
            }
            this.statsObserver = new IntersectionObserver(([entry]) => {
                if (!entry.isIntersecting) return;
                this.statsObserver.disconnect();
                this.countUp();
            }, { threshold: 0.4 });
            this.statsObserver.observe(this.$refs.stats);
        },

        countUp() {
            const start = performance.now();
            const duration = 1600;
            const step = (now) => {
                const t = Math.min((now - start) / duration, 1);
                this.statsProgress = 1 - Math.pow(1 - t, 3);
                if (t < 1) requestAnimationFrame(step);
            };
            requestAnimationFrame(step);
        },
    },
};
</script>

<style scoped>
/* ================= Tokens ================= */
.lt-page {
    --lt-bg: #06071a;
    --lt-bg-alt: #0b0c26;
    --lt-surface: rgba(255, 255, 255, 0.045);
    --lt-surface-strong: rgba(255, 255, 255, 0.08);
    --lt-line: rgba(255, 255, 255, 0.1);
    --lt-text: #eef0ff;
    --lt-muted: #a6a9cc;
    --lt-violet: #8b5cf6;
    --lt-fuchsia: #d946ef;
    --lt-pink: #ec4899;
    --lt-peach: #fdc5c0;
    --lt-cyan: #38bdf8;
    --lt-brand: linear-gradient(115deg, #7c3aed 0%, #d946ef 42%, #fb7185 72%, #fdc5c0 100%);
    /* Para texto con brillo animado: empieza y termina igual para que el bucle no salte */
    --lt-brand-loop: linear-gradient(90deg, #a855f7, #d946ef, #fb7185, #fdc5c0, #d946ef, #a855f7);
    --lt-radius: 28px;

    position: relative;
    min-height: 100vh;
    background: var(--lt-bg);
    color: var(--lt-text);
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    overflow-x: clip;
}

:where(.lt-page) img {
    max-width: 100%;
}

.lt-container {
    position: relative;
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1.25rem;
}

.lt-section {
    position: relative;
    padding: 7rem 0;
}

.lt-section-alt {
    background: var(--lt-bg-alt);
}

.lt-section-tight {
    padding: 3rem 0;
}

.lt-center {
    text-align: center;
}

/* ================= Tipografía ================= */
.lt-head {
    max-width: 780px;
    margin: 0 auto 4rem;
    text-align: center;
}

.lt-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 0.6rem;
    padding: 0.45rem 1rem;
    border: 1px solid var(--lt-line);
    border-radius: 999px;
    background: var(--lt-surface);
    color: #e9d5ff;
    font-size: 0.78rem;
    font-weight: 600;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    backdrop-filter: blur(10px);
}

.lt-title {
    margin: 1.25rem 0 0;
    font-family: 'Outfit', 'Inter', sans-serif;
    font-weight: 800;
    font-size: clamp(2rem, 4.2vw, 3.4rem);
    line-height: 1.08;
    letter-spacing: -0.02em;
    color: #ffffff;
}

.lt-left {
    text-align: left;
}

.lt-sub {
    margin: 1.25rem auto 0;
    max-width: 640px;
    font-size: 1.15rem;
    line-height: 1.6;
    color: var(--lt-muted);
}

.lt-lead {
    margin: 1.25rem 0 2rem;
    font-size: 1.2rem;
    line-height: 1.6;
    color: var(--lt-muted);
}

.lt-fine {
    margin: 0.9rem 0 0;
    font-size: 0.92rem;
    color: var(--lt-muted);
}

.lt-fine i {
    color: var(--lt-violet);
}

.lt-gradient-text {
    background: var(--lt-brand-loop);
    background-size: 200% auto;
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
}

.lt-motion .lt-gradient-text {
    animation: lt-shimmer 6s linear infinite;
}

.lt-link {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    color: #f0abfc;
    font-weight: 600;
    text-decoration: none;
    border-bottom: 1px solid rgba(240, 171, 252, 0.35);
    transition: all 0.2s ease;
}

.lt-link:hover {
    color: #ffffff;
    border-bottom-color: #ffffff;
}

/* ================= Botones ================= */
.lt-btn {
    position: relative;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.6rem;
    padding: 0.8rem 1.4rem;
    border-radius: 999px;
    font-weight: 700;
    font-size: 0.98rem;
    text-decoration: none;
    white-space: nowrap;
    overflow: hidden;
    isolation: isolate;
    transition: transform 0.25s ease, box-shadow 0.25s ease, background 0.25s ease;
}

.lt-btn i {
    transition: transform 0.25s ease;
}

.lt-btn:hover i.fa-arrow-right {
    transform: translateX(4px);
}

.lt-btn-sm {
    padding: 0.55rem 1.1rem;
    font-size: 0.88rem;
}

.lt-btn-lg {
    padding: 1.05rem 1.9rem;
    font-size: 1.05rem;
}

.lt-btn-block {
    width: 100%;
}

.lt-btn-primary {
    background: var(--lt-brand);
    background-size: 160% auto;
    color: #ffffff;
    box-shadow: 0 10px 30px -8px rgba(217, 70, 239, 0.6);
}

/* Destello que cruza el botón */
.lt-btn-primary::after {
    content: '';
    position: absolute;
    inset: 0;
    z-index: -1;
    background: linear-gradient(110deg, transparent 30%, rgba(255, 255, 255, 0.45) 50%, transparent 70%);
    transform: translateX(-120%);
}

.lt-motion .lt-btn-primary::after {
    animation: lt-sheen 4.5s ease-in-out infinite;
}

.lt-btn-primary:hover {
    color: #ffffff;
    background-position: right center;
    transform: translateY(-2px);
    box-shadow: 0 16px 40px -10px rgba(217, 70, 239, 0.75);
}

.lt-btn-glass {
    background: var(--lt-surface);
    border: 1px solid var(--lt-line);
    color: #ffffff;
    backdrop-filter: blur(12px);
}

.lt-btn-glass:hover {
    color: #ffffff;
    background: var(--lt-surface-strong);
    transform: translateY(-2px);
}

.lt-btn-light {
    background: #ffffff;
    color: #4c1d95;
    box-shadow: 0 12px 30px -10px rgba(0, 0, 0, 0.5);
}

.lt-btn-light:hover {
    color: #4c1d95;
    transform: translateY(-2px);
}

.lt-btn-whatsapp {
    background: #25d366;
    color: #06240f;
}

.lt-btn-whatsapp:hover {
    color: #06240f;
    transform: translateY(-2px);
    box-shadow: 0 12px 30px -10px rgba(37, 211, 102, 0.7);
}

.lt-play {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: #ffffff;
    color: #7c3aed;
    font-size: 0.7rem;
}

/* ================= Progreso y navegación ================= */
.lt-progress {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 60;
    height: 3px;
    background: var(--lt-brand);
    transform-origin: left;
}

.lt-nav {
    position: fixed;
    top: 16px;
    left: 50%;
    z-index: 50;
    display: flex;
    align-items: center;
    gap: 1.5rem;
    width: min(1160px, calc(100% - 2rem));
    padding: 0.6rem 0.6rem 0.6rem 1rem;
    border: 1px solid transparent;
    border-radius: 999px;
    transform: translateX(-50%);
    transition: all 0.35s ease;
}

.lt-nav.is-scrolled {
    top: 10px;
    border-color: var(--lt-line);
    background: rgba(10, 11, 34, 0.72);
    backdrop-filter: blur(16px) saturate(140%);
    box-shadow: 0 12px 40px -12px rgba(0, 0, 0, 0.6);
}

.lt-nav-brand {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #ffffff;
    text-decoration: none;
}

.lt-nav-brand:hover {
    color: #ffffff;
}

.lt-nav-brand img {
    width: 34px;
    height: 34px;
}

.lt-nav-brand span {
    font-family: 'Rammetto One', 'Outfit', sans-serif;
    font-size: 1.1rem;
    white-space: nowrap;
}

.lt-nav-links {
    display: flex;
    gap: 0.25rem;
    margin-left: auto;
}

.lt-nav-actions {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.lt-nav-links a {
    padding: 0.45rem 0.8rem;
    border-radius: 999px;
    color: var(--lt-muted);
    font-size: 0.9rem;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s ease;
}

.lt-nav-links a:hover {
    color: #ffffff;
    background: var(--lt-surface-strong);
}

/* ================= Hero ================= */
.lt-hero {
    position: relative;
    min-height: 100vh;
    display: flex;
    align-items: center;
    padding: 7.5rem 0 5rem;
    overflow: hidden;
}

.lt-aurora {
    position: absolute;
    inset: 0;
    pointer-events: none;
}

.lt-blob {
    position: absolute;
    border-radius: 50%;
    opacity: 0.75;
}

.lt-blob-1 {
    width: 60vw;
    height: 60vw;
    top: -25vw;
    right: -15vw;
    background: radial-gradient(circle, rgba(217, 70, 239, 0.55), transparent 62%);
}

.lt-blob-2 {
    width: 55vw;
    height: 55vw;
    bottom: -25vw;
    left: -18vw;
    background: radial-gradient(circle, rgba(124, 58, 237, 0.6), transparent 62%);
}

.lt-blob-3 {
    width: 36vw;
    height: 36vw;
    top: 30%;
    left: 38%;
    background: radial-gradient(circle, rgba(253, 197, 192, 0.28), transparent 65%);
}

.lt-motion .lt-blob-1 { animation: lt-drift 18s ease-in-out infinite alternate; }
.lt-motion .lt-blob-2 { animation: lt-drift 22s ease-in-out infinite alternate-reverse; }
.lt-motion .lt-blob-3 { animation: lt-drift 16s ease-in-out infinite alternate; }

.lt-grid-lines {
    position: absolute;
    inset: 0;
    background-image:
        linear-gradient(rgba(255, 255, 255, 0.05) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255, 255, 255, 0.05) 1px, transparent 1px);
    background-size: 64px 64px;
    -webkit-mask-image: radial-gradient(ellipse at 50% 40%, #000 20%, transparent 70%);
    mask-image: radial-gradient(ellipse at 50% 40%, #000 20%, transparent 70%);
    pointer-events: none;
}

.lt-hero-inner {
    display: grid;
    grid-template-columns: 1.1fr 0.9fr;
    align-items: center;
    gap: 3rem;
}

.lt-pulse {
    position: relative;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #4ade80;
}

.lt-pulse::after {
    content: '';
    position: absolute;
    inset: 0;
    border-radius: 50%;
    background: #4ade80;
}

.lt-motion .lt-pulse::after {
    animation: lt-ping 1.8s ease-out infinite;
}

.lt-hero-title {
    margin: 1.5rem 0 0;
    font-family: 'Outfit', 'Inter', sans-serif;
    font-weight: 800;
    font-size: clamp(2.6rem, 5.6vw, 4.9rem);
    line-height: 1.02;
    letter-spacing: -0.035em;
    color: #ffffff;
}

.lt-word {
    display: inline-block;
}

.lt-hero-list {
    list-style: none;
    margin: 2rem 0 0;
    padding: 0;
    display: grid;
    gap: 0.85rem;
}

.lt-hero-list li {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    font-size: 1.1rem;
    line-height: 1.5;
    color: #d6d8f5;
}

.lt-check {
    flex-shrink: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 24px;
    height: 24px;
    margin-top: 0.1rem;
    border-radius: 50%;
    background: var(--lt-brand);
    color: #ffffff;
    font-size: 0.7rem;
}

.lt-hero-ctas {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    margin-top: 2.5rem;
}

.lt-hero-note {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 0.5rem 1.25rem;
    margin: 1.25rem 0 0;
    font-size: 0.95rem;
    color: var(--lt-muted);
}

.lt-hero-note strong {
    color: #ffffff;
}

/* Entrada escalonada del hero, al cargar */
.lt-motion .lt-word,
.lt-motion .lt-intro {
    opacity: 0;
    animation: lt-rise 0.9s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
    animation-delay: calc(var(--i, 0) * 70ms + 100ms);
}

.lt-hero-visual {
    position: relative;
    display: flex;
    justify-content: center;
}

.lt-phone-stage {
    position: relative;
    width: min(100%, 470px);
    transform-style: preserve-3d;
    transition: transform 0.4s cubic-bezier(0.2, 0.8, 0.2, 1);
}

.lt-halo {
    position: absolute;
    inset: 12% 8%;
    border-radius: 50%;
    background: conic-gradient(from 0deg, #7c3aed, #d946ef, #fb7185, #fdc5c0, #38bdf8, #7c3aed);
    filter: blur(60px);
    opacity: 0.55;
}

.lt-motion .lt-halo {
    animation: lt-spin 14s linear infinite;
}

.lt-hero-phone {
    position: relative;
    display: block;
    width: 100%;
    filter: drop-shadow(0 40px 60px rgba(0, 0, 0, 0.55));
}

.lt-motion .lt-hero-phone {
    animation: lt-float 6s ease-in-out infinite;
}

.lt-chip {
    position: absolute;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.6rem 1rem;
    border: 1px solid var(--lt-line);
    border-radius: 999px;
    background: rgba(14, 15, 44, 0.7);
    backdrop-filter: blur(12px);
    color: #ffffff;
    font-size: 0.88rem;
    font-weight: 600;
    white-space: nowrap;
    box-shadow: 0 12px 30px -10px rgba(0, 0, 0, 0.6);
    transform: translateZ(60px);
}

.lt-chip i {
    font-size: 1rem;
}

.lt-chip-1 { top: 12%; left: -8%; }
.lt-chip-1 i { color: #25d366; }
.lt-chip-2 { top: 34%; right: -6%; }
.lt-chip-2 i { color: #c4b5fd; }
.lt-chip-3 { bottom: 26%; left: -12%; }
.lt-chip-3 i { color: #fb7185; }
.lt-chip-4 { bottom: 8%; right: 0; }
.lt-chip-4 i { color: #38bdf8; }

.lt-motion .lt-chip { animation: lt-bob 5s ease-in-out infinite; }
.lt-motion .lt-chip-2 { animation-delay: -1.2s; }
.lt-motion .lt-chip-3 { animation-delay: -2.4s; }
.lt-motion .lt-chip-4 { animation-delay: -3.6s; }

.lt-scroll-cue {
    position: absolute;
    bottom: 1.75rem;
    left: 50%;
    width: 26px;
    height: 42px;
    padding: 0;
    border: 2px solid rgba(255, 255, 255, 0.35);
    border-radius: 999px;
    background: transparent;
    transform: translateX(-50%);
    cursor: pointer;
}

.lt-scroll-cue span {
    position: absolute;
    top: 8px;
    left: 50%;
    width: 4px;
    height: 8px;
    margin-left: -2px;
    border-radius: 2px;
    background: #ffffff;
}

.lt-motion .lt-scroll-cue span {
    animation: lt-wheel 1.8s ease-in-out infinite;
}

/* ================= Marquee ================= */
.lt-marquee {
    position: relative;
    padding: 1.1rem 0;
    border-block: 1px solid var(--lt-line);
    background: linear-gradient(90deg, rgba(124, 58, 237, 0.15), rgba(217, 70, 239, 0.12), rgba(251, 113, 133, 0.12));
    overflow: hidden;
    -webkit-mask-image: linear-gradient(90deg, transparent, #000 10%, #000 90%, transparent);
    mask-image: linear-gradient(90deg, transparent, #000 10%, #000 90%, transparent);
}

.lt-marquee-track {
    display: flex;
    gap: 2.75rem;
    width: max-content;
}

.lt-motion .lt-marquee-track {
    animation: lt-marquee 45s linear infinite;
}

.lt-marquee-item {
    display: inline-flex;
    align-items: center;
    gap: 0.6rem;
    font-family: 'Outfit', 'Inter', sans-serif;
    font-size: 1.15rem;
    font-weight: 600;
    color: #e9e3ff;
    white-space: nowrap;
}

.lt-marquee-item i {
    color: #f0abfc;
}

/* ================= Tarjetas (glass + luz) ================= */
.lt-card {
    position: relative;
    border: 1px solid var(--lt-line);
    border-radius: var(--lt-radius);
    background: linear-gradient(160deg, rgba(255, 255, 255, 0.07), rgba(255, 255, 255, 0.02));
    overflow: hidden;
    transition: transform 0.35s ease, border-color 0.35s ease, box-shadow 0.35s ease;
}

.lt-spot::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(420px circle at var(--mx, 50%) var(--my, 0%), rgba(217, 70, 239, 0.18), transparent 45%);
    opacity: 0;
    transition: opacity 0.35s ease;
    pointer-events: none;
}

.lt-spot:hover::before {
    opacity: 1;
}

.lt-spot:hover {
    transform: translateY(-4px);
    border-color: rgba(217, 70, 239, 0.4);
    box-shadow: 0 24px 50px -24px rgba(217, 70, 239, 0.45);
}

/* ================= Bento de funciones ================= */
.lt-bento {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.1rem;
}

.lt-bento .lt-card {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
    padding: 1.6rem;
}

.lt-card-wide {
    grid-column: span 2;
    flex-direction: row !important;
    align-items: center;
    min-height: 170px;
}

.lt-icon-tile {
    flex-shrink: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 54px;
    height: 54px;
    border-radius: 16px;
    background: var(--lt-brand);
    color: #ffffff;
    font-size: 1.4rem;
    box-shadow: 0 10px 24px -8px rgba(217, 70, 239, 0.6);
}

.lt-card-wide .lt-icon-tile {
    width: 76px;
    height: 76px;
    border-radius: 22px;
    font-size: 2rem;
}

.lt-bento h3 {
    margin: 0;
    font-family: 'Outfit', 'Inter', sans-serif;
    font-size: 1.15rem;
    font-weight: 700;
    color: #ffffff;
}

.lt-card-wide h3 {
    font-size: 1.5rem;
}

.lt-bento p {
    margin: 0.35rem 0 0;
    font-size: 0.95rem;
    line-height: 1.5;
    color: var(--lt-muted);
}

.lt-card-wide p {
    font-size: 1.05rem;
}

/* ================= Pasos ================= */
.lt-steps {
    position: relative;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 2rem;
    margin-bottom: 4rem;
}

.lt-steps-line {
    position: absolute;
    top: 44px;
    left: 16%;
    right: 16%;
    height: 2px;
    background: var(--lt-brand);
    transform: scaleX(0);
    transform-origin: left;
    transition: transform 1.4s cubic-bezier(0.2, 0.8, 0.2, 1) 0.2s;
}

.lt-steps.is-in .lt-steps-line,
.lt-page:not(.lt-motion) .lt-steps-line {
    transform: scaleX(1);
}

.lt-step {
    position: relative;
    text-align: center;
}

.lt-motion .lt-step {
    opacity: 0;
    transform: translateY(24px);
    transition: opacity 0.7s ease, transform 0.7s ease;
    transition-delay: calc(var(--i) * 250ms + 300ms);
}

.lt-steps.is-in .lt-step {
    opacity: 1;
    transform: none;
}

.lt-step-orb {
    position: relative;
    z-index: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 88px;
    height: 88px;
    border-radius: 50%;
    background: var(--lt-bg-alt);
    border: 2px solid transparent;
    background-image: linear-gradient(var(--lt-bg-alt), var(--lt-bg-alt)), var(--lt-brand);
    background-origin: border-box;
    background-clip: padding-box, border-box;
    color: #f0abfc;
    font-size: 2rem;
    box-shadow: 0 0 40px -6px rgba(217, 70, 239, 0.55);
}

.lt-step-num {
    display: block;
    margin-top: 1.25rem;
    font-family: 'Outfit', 'Inter', sans-serif;
    font-size: 3.4rem;
    font-weight: 800;
    line-height: 1;
    color: transparent;
    -webkit-text-stroke: 1.5px rgba(240, 171, 252, 0.55);
}

.lt-step h3 {
    margin: 0.6rem 0 0.6rem;
    font-family: 'Outfit', 'Inter', sans-serif;
    font-size: 1.45rem;
    font-weight: 700;
    color: #ffffff;
}

.lt-step p {
    max-width: 300px;
    margin: 0 auto;
    font-size: 1rem;
    line-height: 1.6;
    color: var(--lt-muted);
}

/* ================= Cifras ================= */
.lt-stats-band {
    position: relative;
    padding: 5rem 0;
    background:
        radial-gradient(ellipse at 20% 50%, rgba(124, 58, 237, 0.35), transparent 60%),
        radial-gradient(ellipse at 80% 50%, rgba(236, 72, 153, 0.3), transparent 60%),
        var(--lt-bg);
    border-block: 1px solid var(--lt-line);
}

.lt-stats-tagline {
    margin: 0 auto 3rem;
    max-width: 720px;
    text-align: center;
    font-family: 'Outfit', 'Inter', sans-serif;
    font-size: clamp(1.3rem, 2.4vw, 1.8rem);
    font-weight: 600;
    line-height: 1.35;
    color: #ffffff;
}

.lt-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.5rem;
}

.lt-stat {
    text-align: center;
    padding: 0 0.5rem;
}

.lt-stat + .lt-stat {
    border-left: 1px solid var(--lt-line);
}

.lt-stat strong {
    display: block;
    font-family: 'Outfit', 'Inter', sans-serif;
    font-size: clamp(2.8rem, 5vw, 4.2rem);
    font-weight: 800;
    line-height: 1;
    font-variant-numeric: tabular-nums;
}

.lt-stat span {
    display: block;
    max-width: 220px;
    margin: 0.75rem auto 0;
    font-size: 0.95rem;
    line-height: 1.45;
    color: var(--lt-muted);
}

/* ================= Testimonios ================= */
.lt-testimonials {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
}

.lt-quote-card {
    display: flex;
    flex-direction: column;
    margin: 0;
    padding: 2rem;
}

.lt-quote-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.lt-quote-mark {
    font-family: Georgia, serif;
    font-size: 5rem;
    line-height: 0.6;
    height: 2.2rem;
    background: var(--lt-brand);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
}

.lt-stars {
    color: #fbbf24;
    font-size: 0.95rem;
    letter-spacing: 0.15rem;
}

.lt-quote-card blockquote {
    flex: 1;
    margin: 1.5rem 0 2rem;
    font-size: 1.12rem;
    line-height: 1.65;
    color: #e4e6ff;
}

.lt-quote-card figcaption {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--lt-line);
}

.lt-avatar {
    flex-shrink: 0;
    width: 64px;
    height: 64px;
    padding: 4px;
    border-radius: 50%;
    background: #ffffff;
    box-shadow: 0 0 0 2px rgba(217, 70, 239, 0.5);
}

.lt-avatar img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: contain;
}

.lt-author {
    display: flex;
    flex-direction: column;
    gap: 0.15rem;
    font-size: 0.88rem;
    color: var(--lt-muted);
}

.lt-author strong {
    font-size: 1.05rem;
    color: #ffffff;
}

.lt-place i {
    color: #f0abfc;
}

/* ================= Precio ================= */
.lt-pricing-section {
    overflow: hidden;
}

.lt-glow {
    position: absolute;
    pointer-events: none;
}

.lt-glow-center {
    top: 12%;
    left: 50%;
    width: 900px;
    height: 900px;
    transform: translateX(-50%);
    background: radial-gradient(circle, rgba(217, 70, 239, 0.22), transparent 60%);
}

.lt-price-card {
    position: relative;
    max-width: 1040px;
    margin: 0 auto;
    padding: 2px;
    border-radius: 34px;
    overflow: hidden;
    box-shadow: 0 40px 100px -40px rgba(217, 70, 239, 0.6);
}

/* Borde de degradado que gira */
.lt-price-card::before {
    content: '';
    position: absolute;
    inset: -60%;
    background: conic-gradient(from 0deg, #7c3aed, #d946ef, #fb7185, #fdc5c0, #38bdf8, #7c3aed);
}

.lt-motion .lt-price-card::before {
    animation: lt-spin 8s linear infinite;
}

.lt-price-inner {
    position: relative;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 3rem;
    padding: 3.25rem;
    border-radius: 32px;
    background: linear-gradient(160deg, #11123a, #0a0b24 60%);
}

.lt-plan-tag {
    display: inline-block;
    padding: 0.4rem 0.9rem;
    border-radius: 999px;
    background: rgba(217, 70, 239, 0.15);
    color: #f0abfc;
    font-size: 0.8rem;
    font-weight: 700;
    letter-spacing: 0.14em;
}

.lt-price-before {
    margin: 1.5rem 0 0;
    font-size: 1.15rem;
    color: var(--lt-muted);
}

.lt-price-before s {
    text-decoration-color: #fb7185;
    text-decoration-thickness: 2px;
}

.lt-price-today {
    margin: 0.5rem 0 0;
    font-size: 1rem;
    font-weight: 600;
    color: #ffffff;
}

.lt-price {
    display: flex;
    align-items: flex-start;
    margin: 0.25rem 0 0;
    font-family: 'Outfit', 'Inter', sans-serif;
    font-weight: 800;
    font-size: clamp(3.6rem, 7vw, 5.6rem);
    line-height: 1;
    letter-spacing: -0.03em;
}

.lt-price-currency {
    margin-top: 0.4rem;
    font-size: 0.45em;
    color: #f0abfc;
}

.lt-price-period {
    align-self: flex-end;
    margin: 0 0 0.6rem 0.5rem;
    font-size: 0.26em;
    font-weight: 500;
    letter-spacing: 0;
    color: var(--lt-muted);
}

.lt-price-after {
    margin: 0.5rem 0 0;
    font-size: 0.88rem;
    color: var(--lt-muted);
}

.lt-save {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    margin: 1.25rem 0 2rem;
    padding: 0.55rem 1rem;
    border: 1px solid rgba(74, 222, 128, 0.35);
    border-radius: 999px;
    background: rgba(74, 222, 128, 0.12);
    color: #86efac;
    font-weight: 700;
}

.lt-perks {
    list-style: none;
    display: grid;
    gap: 0.9rem;
    margin: 2rem 0 0;
    padding: 0;
    color: #d6d8f5;
}

.lt-perks li {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.lt-perks i {
    width: 20px;
    text-align: center;
    color: #c4b5fd;
}

.lt-price-includes {
    padding-left: 3rem;
    border-left: 1px solid var(--lt-line);
}

.lt-price-includes h3 {
    margin: 0 0 1.5rem;
    font-family: 'Outfit', 'Inter', sans-serif;
    font-size: 1.5rem;
    font-weight: 700;
    color: #ffffff;
}

.lt-price-includes ul {
    list-style: none;
    display: grid;
    gap: 0.9rem;
    margin: 0;
    padding: 0;
}

.lt-price-includes li {
    display: flex;
    align-items: center;
    gap: 0.9rem;
    font-size: 1.05rem;
    color: #e4e6ff;
}

.lt-inc-icon {
    flex-shrink: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 38px;
    height: 38px;
    border-radius: 12px;
    background: rgba(139, 92, 246, 0.18);
    border: 1px solid rgba(139, 92, 246, 0.35);
    color: #e9d5ff;
    font-size: 0.95rem;
}

.lt-trust {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    margin: 3rem 0 0;
    font-size: 1.1rem;
    font-weight: 600;
    color: #d6d8f5;
}

.lt-trust i {
    color: #86efac;
    font-size: 1.3rem;
}

.lt-promo {
    display: grid;
    grid-template-columns: 0.9fr 1.1fr;
    align-items: center;
    gap: 3rem;
    margin-top: 7rem;
}

.lt-promo-visual,
.lt-contact-visual {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.lt-ring {
    position: absolute;
    top: 50%;
    left: 50%;
    width: min(420px, 90%);
    aspect-ratio: 1;
    border-radius: 50%;
    border: 1px solid rgba(240, 171, 252, 0.35);
    box-shadow:
        0 0 0 40px rgba(217, 70, 239, 0.05),
        0 0 0 80px rgba(217, 70, 239, 0.03),
        0 0 120px rgba(217, 70, 239, 0.35) inset;
    transform: translate(-50%, -50%);
}

.lt-promo-phone {
    position: relative;
    width: 100%;
    max-width: 290px;
    filter: drop-shadow(0 40px 60px rgba(0, 0, 0, 0.6));
    transition: transform 0.2s linear;
}

/* ================= FAQ ================= */
.lt-faq {
    display: grid;
    grid-template-columns: 0.85fr 1.15fr;
    gap: 4rem;
    align-items: start;
}

.lt-faq-art {
    display: block;
    width: 78%;
    margin: 1rem auto 1.5rem;
}

.lt-motion .lt-faq-art {
    animation: lt-float 7s ease-in-out infinite;
}

.lt-faq-help {
    padding: 1.75rem;
}

.lt-faq-help h3 {
    margin: 0;
    font-family: 'Outfit', 'Inter', sans-serif;
    font-size: 1.35rem;
    font-weight: 700;
    color: #ffffff;
}

.lt-faq-help p {
    margin: 0.6rem 0 1.25rem;
    line-height: 1.6;
    color: var(--lt-muted);
}

.lt-accordion {
    display: grid;
    gap: 0.85rem;
}

.lt-acc-item {
    border: 1px solid var(--lt-line);
    border-radius: 20px;
    background: var(--lt-surface);
    transition: border-color 0.3s ease, background 0.3s ease;
}

.lt-acc-item.is-open {
    border-color: rgba(217, 70, 239, 0.45);
    background: linear-gradient(160deg, rgba(217, 70, 239, 0.1), rgba(124, 58, 237, 0.06));
}

.lt-acc-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    width: 100%;
    padding: 1.25rem 1.5rem;
    border: 0;
    background: transparent;
    color: #ffffff;
    font-family: 'Outfit', 'Inter', sans-serif;
    font-size: 1.12rem;
    font-weight: 600;
    text-align: left;
    cursor: pointer;
}

.lt-acc-sign {
    position: relative;
    flex-shrink: 0;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: var(--lt-surface-strong);
}

.lt-acc-sign::before,
.lt-acc-sign::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 12px;
    height: 2px;
    margin: -1px 0 0 -6px;
    border-radius: 2px;
    background: #f0abfc;
    transition: transform 0.3s ease;
}

.lt-acc-sign::after {
    transform: rotate(90deg);
}

.is-open .lt-acc-sign::after {
    transform: rotate(0deg);
}

/* Altura animada sin medir: 0fr → 1fr */
.lt-acc-body {
    display: grid;
    grid-template-rows: 0fr;
    transition: grid-template-rows 0.4s ease;
}

.is-open .lt-acc-body {
    grid-template-rows: 1fr;
}

.lt-acc-body > div {
    overflow: hidden;
}

.lt-acc-body p {
    margin: 0;
    padding: 0 1.5rem 1.4rem;
    line-height: 1.7;
    color: var(--lt-muted);
}

.lt-acc-guide {
    justify-self: start;
    margin-top: 0.75rem;
}

/* ================= Únete ================= */
.lt-join {
    position: relative;
    display: grid;
    grid-template-columns: 0.8fr 1.2fr;
    align-items: center;
    gap: 3rem;
    padding: 3.5rem 4rem;
    border-radius: 40px;
    background: linear-gradient(115deg, #5b21b6 0%, #a21caf 50%, #db2777 100%);
    overflow: hidden;
}

.lt-join-mesh {
    position: absolute;
    inset: 0;
    background:
        radial-gradient(circle at 15% 20%, rgba(6, 7, 26, 0.55), transparent 45%),
        radial-gradient(circle at 90% 90%, rgba(255, 255, 255, 0.25), transparent 40%);
}

.lt-motion .lt-join-mesh {
    animation: lt-mesh 10s ease-in-out infinite alternate;
}

.lt-join-phone {
    position: relative;
    width: 100%;
    max-width: 280px;
    justify-self: center;
    transform: rotate(-8deg);
    filter: drop-shadow(0 30px 50px rgba(6, 7, 26, 0.55));
}

.lt-join-copy {
    position: relative;
}

.lt-join .lt-title,
.lt-join .lt-lead {
    color: #ffffff;
}

.lt-join .lt-lead {
    opacity: 0.9;
}

.lt-fine-light,
.lt-fine-light i {
    color: rgba(255, 255, 255, 0.9);
}

/* ================= Contacto ================= */
.lt-contact {
    display: grid;
    grid-template-columns: 1.35fr 0.65fr;
    gap: 3rem;
    align-items: center;
}

.lt-contact-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.1rem;
}

.lt-contact-card {
    --c: #8b5cf6;
    padding: 1.75rem;
}

.lt-c-whatsapp { --c: #25d366; }
.lt-c-email    { --c: #a855f7; }
.lt-c-demo     { --c: #fb7185; }
.lt-c-guide    { --c: #60a5fa; }

.lt-contact-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 52px;
    height: 52px;
    border-radius: 16px;
    background: color-mix(in srgb, var(--c) 18%, transparent);
    border: 1px solid color-mix(in srgb, var(--c) 45%, transparent);
    color: var(--c);
    font-size: 1.4rem;
    box-shadow: 0 0 30px -6px var(--c);
}

.lt-contact-card h3 {
    margin: 1.1rem 0 0.4rem;
    font-family: 'Outfit', 'Inter', sans-serif;
    font-size: 1.2rem;
    font-weight: 700;
    color: #ffffff;
}

.lt-contact-card p {
    margin: 0 0 1.1rem;
    line-height: 1.55;
    color: var(--lt-muted);
}

.lt-contact-link {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--c);
    font-weight: 700;
    text-decoration: none;
}

.lt-contact-link:hover {
    color: #ffffff;
}

.lt-contact-link i {
    transition: transform 0.25s ease;
}

.lt-contact-link:hover i {
    transform: translateX(4px);
}

.lt-contact-card .lt-contact-note {
    margin: 1rem 0 0;
    font-size: 0.82rem;
    line-height: 1.5;
}

.lt-contact-visual {
    gap: 2rem;
}

.lt-ring-soft {
    top: 42%;
}

.lt-contact-phone {
    position: relative;
    width: 100%;
    max-width: 340px;
    filter: drop-shadow(0 30px 50px rgba(0, 0, 0, 0.6));
}

.lt-motion .lt-contact-phone {
    animation: lt-float 7s ease-in-out infinite;
}

.lt-banner {
    display: grid;
    grid-template-columns: auto 1fr auto;
    align-items: center;
    gap: 2rem;
    margin-top: 5rem;
    padding: 1.5rem 2rem 1.5rem 1.5rem;
    border: 1px solid var(--lt-line);
    border-radius: 32px;
    background: linear-gradient(120deg, rgba(124, 58, 237, 0.25), rgba(236, 72, 153, 0.15));
}

.lt-banner-photo {
    width: 220px;
    border-radius: 20px;
}

.lt-banner-copy h3 {
    margin: 0;
    font-family: 'Outfit', 'Inter', sans-serif;
    font-size: 1.4rem;
    font-weight: 700;
    line-height: 1.3;
    color: #ffffff;
}

.lt-banner-copy p {
    margin: 0.4rem 0 0;
    font-size: 1.1rem;
    font-weight: 700;
}

/* ================= Footer ================= */
.lt-footer {
    position: relative;
    padding: 4.5rem 0 2rem;
    border-top: 1px solid var(--lt-line);
    background: #04050f;
    overflow: hidden;
}

.lt-footer-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 2rem;
}

.lt-footer-brand {
    display: flex;
    align-items: center;
    gap: 0.6rem;
}

.lt-footer-brand img {
    width: 48px;
    height: 48px;
}

.lt-footer-brand span {
    font-family: 'Rammetto One', 'Outfit', sans-serif;
    font-size: 1.8rem;
}

.lt-footer-top p {
    margin: 0.75rem 0 0;
    color: var(--lt-muted);
}

.lt-footer-social {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: var(--lt-muted);
}

.lt-footer-social span {
    margin-right: 0.5rem;
}

.lt-footer-social a {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 46px;
    height: 46px;
    border: 1px solid var(--lt-line);
    border-radius: 50%;
    color: #ffffff;
    font-size: 1.15rem;
    text-decoration: none;
    transition: all 0.25s ease;
}

.lt-footer-social a:hover {
    background: var(--lt-brand);
    border-color: transparent;
    color: #ffffff;
    transform: translateY(-3px);
}

.lt-footer-bottom {
    position: relative;
    z-index: 1;
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1rem;
    margin-top: 3.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--lt-line);
    font-size: 0.9rem;
    color: var(--lt-muted);
}

.lt-footer-legal {
    display: flex;
    gap: 1.5rem;
}

.lt-footer-legal a {
    color: var(--lt-muted);
    text-decoration: none;
}

.lt-footer-legal a:hover {
    color: #ffffff;
}

.lt-footer-word {
    margin: 2rem 0 -3.5vw;
    text-align: center;
    font-family: 'Rammetto One', 'Outfit', sans-serif;
    font-size: 19vw;
    line-height: 1;
    background: linear-gradient(180deg, rgba(217, 70, 239, 0.22), rgba(217, 70, 239, 0) 85%);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    user-select: none;
    pointer-events: none;
}

/* ================= Aparición al hacer scroll ================= */
.lt-motion .lt-reveal {
    opacity: 0;
    transform: translateY(36px);
    transition: opacity 0.9s cubic-bezier(0.2, 0.8, 0.2, 1), transform 0.9s cubic-bezier(0.2, 0.8, 0.2, 1);
    transition-delay: var(--d, 0ms);
}

.lt-motion .lt-reveal.is-in {
    opacity: 1;
    transform: none;
}

/* Las tarjetas con hover necesitan su propio transform una vez visibles */
.lt-motion .lt-reveal.is-in.lt-spot:hover {
    transform: translateY(-4px);
    transition-delay: 0ms;
}

/* ================= Animaciones ================= */
@keyframes lt-rise {
    from { opacity: 0; transform: translateY(28px); filter: blur(6px); }
    to   { opacity: 1; transform: none; filter: blur(0); }
}

@keyframes lt-shimmer {
    to { background-position: 200% center; }
}

@keyframes lt-sheen {
    0%, 55% { transform: translateX(-120%); }
    80%, 100% { transform: translateX(120%); }
}

@keyframes lt-drift {
    0%   { transform: translate(0, 0) scale(1); }
    50%  { transform: translate(-6vw, 4vw) scale(1.1); }
    100% { transform: translate(4vw, -3vw) scale(0.95); }
}

@keyframes lt-spin {
    to { transform: rotate(360deg); }
}

@keyframes lt-float {
    0%, 100% { translate: 0 0; }
    50%      { translate: 0 -14px; }
}

@keyframes lt-bob {
    0%, 100% { translate: 0 0; }
    50%      { translate: 0 -10px; }
}

@keyframes lt-ping {
    0%   { transform: scale(1); opacity: 0.8; }
    100% { transform: scale(3); opacity: 0; }
}

@keyframes lt-wheel {
    0%   { opacity: 1; transform: translateY(0); }
    100% { opacity: 0; transform: translateY(14px); }
}

@keyframes lt-marquee {
    to { transform: translateX(-50%); }
}

@keyframes lt-mesh {
    to { transform: scale(1.15) translate(3%, -3%); }
}

/* ================= Tablet ================= */
@media (max-width: 1080px) {
    .lt-nav-links {
        display: none;
    }

    .lt-nav-actions {
        margin-left: auto;
    }

    .lt-price-inner {
        padding: 2.5rem;
        gap: 2rem;
    }

    .lt-price-includes {
        padding-left: 2rem;
    }

    .lt-contact {
        grid-template-columns: 1fr;
    }

    .lt-contact-visual {
        display: none;
    }
}

/* ================= Mobile ================= */
@media (max-width: 860px) {
    .lt-section {
        padding: 4.5rem 0;
    }

    .lt-head {
        margin-bottom: 2.5rem;
    }

    .lt-hero {
        min-height: auto;
        padding: 6.5rem 0 4.5rem;
    }

    .lt-eyebrow {
        letter-spacing: 0.08em;
    }

    .lt-nav-brand span {
        font-size: 0.95rem;
    }

    .lt-nav {
        gap: 0.75rem;
        padding-left: 0.75rem;
    }

    .lt-nav-actions .lt-btn-sm {
        padding: 0.5rem 0.8rem;
        font-size: 0.8rem;
    }
}

/* Teléfonos angostos: el logo queda solo con el símbolo para que quepan los dos botones */
@media (max-width: 480px) {
    .lt-nav-brand span {
        display: none;
    }

    .lt-hero-inner,
    .lt-faq,
    .lt-promo,
    .lt-join,
    .lt-price-inner {
        grid-template-columns: 1fr;
    }

    .lt-hero-visual {
        margin-top: 1rem;
    }

    .lt-phone-stage {
        width: min(100%, 340px);
    }

    .lt-chip {
        font-size: 0.76rem;
        padding: 0.45rem 0.75rem;
    }

    .lt-chip-1 { left: -2%; }
    .lt-chip-2 { right: -2%; }
    .lt-chip-3 { left: -4%; }

    .lt-hero-ctas .lt-btn {
        flex: 1 1 100%;
    }

    .lt-scroll-cue {
        display: none;
    }

    .lt-bento {
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem;
    }

    .lt-bento .lt-card {
        padding: 1.25rem;
        gap: 0.9rem;
        border-radius: 22px;
    }

    .lt-card-wide {
        min-height: 0;
    }

    .lt-card-wide .lt-icon-tile {
        width: 58px;
        height: 58px;
        font-size: 1.5rem;
    }

    .lt-card-wide h3 {
        font-size: 1.2rem;
    }

    .lt-bento h3 {
        font-size: 1rem;
    }

    .lt-bento p,
    .lt-card-wide p {
        font-size: 0.88rem;
    }

    .lt-steps {
        grid-template-columns: 1fr;
        gap: 2.5rem;
    }

    .lt-steps-line {
        top: 44px;
        bottom: 44px;
        left: 50%;
        right: auto;
        width: 2px;
        height: auto;
        transform: scaleY(0);
        transform-origin: top;
        opacity: 0.35;
    }

    .lt-steps.is-in .lt-steps-line,
    .lt-page:not(.lt-motion) .lt-steps-line {
        transform: scaleY(1);
    }

    .lt-step {
        padding: 0.5rem 0 1rem;
        background: var(--lt-bg-alt);
    }

    .lt-stats {
        grid-template-columns: repeat(2, 1fr);
        gap: 2.5rem 1rem;
    }

    .lt-stat + .lt-stat {
        border-left: 0;
    }

    .lt-testimonials {
        grid-template-columns: 1fr;
    }

    .lt-quote-card {
        padding: 1.5rem;
    }

    .lt-price-inner {
        padding: 2rem 1.5rem;
    }

    .lt-price-includes {
        padding: 2rem 0 0;
        border-left: 0;
        border-top: 1px solid var(--lt-line);
    }

    .lt-promo {
        margin-top: 4.5rem;
    }

    .lt-promo-phone {
        max-width: 220px;
    }

    .lt-promo-copy,
    .lt-join-copy,
    .lt-faq-aside {
        text-align: center;
    }

    .lt-left {
        text-align: center;
    }

    .lt-faq {
        gap: 2.5rem;
    }

    .lt-faq-art {
        width: 60%;
    }

    .lt-acc-head {
        font-size: 1rem;
        padding: 1.1rem 1.25rem;
    }

    .lt-acc-body p {
        padding: 0 1.25rem 1.25rem;
    }

    .lt-join {
        padding: 2.5rem 1.5rem;
        border-radius: 30px;
        gap: 2rem;
    }

    .lt-join-phone {
        max-width: 200px;
    }

    .lt-contact-grid {
        grid-template-columns: 1fr;
    }

    .lt-banner {
        grid-template-columns: 1fr;
        justify-items: center;
        text-align: center;
        padding: 1.5rem;
        margin-top: 3.5rem;
    }

    .lt-banner-photo {
        width: 100%;
        max-width: 320px;
    }

    .lt-footer-top,
    .lt-footer-bottom {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .lt-footer-legal {
        flex-wrap: wrap;
        justify-content: center;
    }
}
</style>

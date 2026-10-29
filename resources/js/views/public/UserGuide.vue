<template>
    <div class="lt-page ug-page" :class="{ 'lt-motion': motionOk }">
        <!-- Progreso de lectura -->
        <div class="lt-progress" :style="{ transform: `scaleX(${scrollProgress})` }" aria-hidden="true"></div>

        <!-- ===================== Navegación flotante ===================== -->
        <nav class="lt-nav" :class="{ 'is-scrolled': scrolled }" aria-label="Secciones de la guía">
            <router-link to="/inicio" class="lt-nav-brand" aria-label="Nexos Card">
                <img :src="landingImg('logo-mark')" alt="">
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

        <!-- ===================== Portada ===================== -->
        <header class="lt-hero ug-hero">
            <div class="lt-aurora" aria-hidden="true">
                <span class="lt-blob lt-blob-1"></span>
                <span class="lt-blob lt-blob-2"></span>
                <span class="lt-blob lt-blob-3"></span>
            </div>
            <div class="lt-grid-lines" aria-hidden="true"></div>

            <div class="lt-container ug-hero-inner">
                <div class="ug-hero-copy">
                    <span class="lt-eyebrow lt-intro" style="--i: 0">
                        <i class="fas fa-book-open" aria-hidden="true"></i> Guía oficial
                    </span>
                    <h1 class="ug-hero-title lt-intro" style="--i: 1">
                        Guía de <span class="lt-gradient-text">primeros pasos</span>
                    </h1>
                    <p class="ug-hero-lead lt-intro" style="--i: 2">
                        <i class="fas fa-rocket" aria-hidden="true"></i>
                        Crea tu primera tarjeta digital en pocos minutos.
                    </p>

                    <ul class="ug-hero-chips lt-intro" style="--i: 3">
                        <li v-for="c in heroChips" :key="c.title">
                            <span class="ug-chip-icon"><i :class="c.icon" aria-hidden="true"></i></span>
                            <span><strong>{{ c.title }}</strong> {{ c.text }}</span>
                        </li>
                    </ul>

                    <div class="ug-login-box lt-intro" style="--i: 4">
                        <p class="ug-login-q">¿Ya activaste tu prueba de {{ trialDays }} días?</p>
                        <p>Ingresa a tu panel para subir tu información.</p>
                        <router-link v-if="isAuthenticated" to="/" class="lt-btn lt-btn-primary">
                            <i class="fas fa-hand-point-right" aria-hidden="true"></i> Ir a mi panel
                        </router-link>
                        <router-link v-else to="/login" class="lt-btn lt-btn-primary">
                            <i class="fas fa-hand-point-right" aria-hidden="true"></i> Iniciar Sesión
                        </router-link>
                        <p class="ug-login-small">
                            ¿Aún no tienes una cuenta?
                            <a href="#paso-1" class="lt-link" @click.prevent="goTo('paso-1')">Ve al paso 1</a>
                            para ver cómo registrarte desde cero.
                        </p>
                    </div>
                </div>

                <div class="ug-hero-visual lt-intro" style="--i: 2">
                    <span class="lt-halo" aria-hidden="true"></span>
                    <img :src="guideImg('hero-celular')" alt="Tarjeta digital Nexos Card en un celular" class="ug-hero-phone" width="900" height="600">
                </div>
            </div>
        </header>

        <!-- ===================== Misión 1 ===================== -->
        <section id="mision-1" class="lt-section">
            <div class="lt-container">
                <header class="lt-head" v-reveal>
                    <span class="lt-eyebrow">Misión 1</span>
                    <h2 class="lt-title">El reto de los <span class="lt-gradient-text">2 minutos</span></h2>
                    <p class="lt-sub">Configura tu identidad base en 3 pasos.</p>
                </header>

                <p class="ug-mission-intro" v-reveal>
                    <span class="ug-num"><i class="fas fa-mouse-pointer" aria-hidden="true"></i></span> {{ missionOne.intro }}
                </p>
                <figure class="ug-shot" v-reveal>
                    <button type="button" class="ug-shot-btn" :aria-label="`Ampliar: ${missionOne.introImage.alt}`" @click="zoom(missionOne.introImage)">
                        <GuideClip :name="missionOne.introImage.name" :alt="missionOne.introImage.alt" />
                    </button>
                </figure>
                <p class="ug-mission-after" v-reveal>{{ missionOne.afterIntro }}</p>

                <ol class="ug-mission-steps">
                    <li v-for="(m, i) in missionOne.steps" :key="m.title" v-reveal class="ug-mission-step">
                        <div class="ug-mission-text">
                            <span class="ug-num">{{ i + 1 }}</span>
                            <div>
                                <h3>{{ m.title }}</h3>
                                <p>{{ m.text }}</p>
                            </div>
                        </div>
                        <figure v-if="m.image" class="ug-shot">
                            <button type="button" class="ug-shot-btn" :aria-label="`Ampliar: ${m.image.alt}`" @click="zoom(m.image)">
                                <GuideClip :name="m.image.name" :alt="m.image.alt" />
                            </button>
                        </figure>
                    </li>
                </ol>

                <div class="ug-congrats" v-reveal>
                    <i class="fas fa-star" aria-hidden="true"></i>
                    <div>
                        <strong>¡Felicidades!</strong>
                        <span>Tu tarjeta ya está en vivo</span>
                    </div>
                    <i class="fas fa-star" aria-hidden="true"></i>
                </div>
            </div>
        </section>

        <!-- ===================== Misión 2 + Bienvenida ===================== -->
        <section id="antes-de-empezar" class="lt-section lt-section-alt">
            <div class="lt-container">
                <header class="lt-head" v-reveal>
                    <span class="lt-eyebrow">Misión 2</span>
                    <h2 class="lt-title">Completa tu <span class="lt-gradient-text">tarjeta interactiva</span></h2>
                    <p class="lt-sub">
                        Termina de configurar tu tarjeta en unos pocos pasos para que la compartas y disfrutes
                        de todas las ventajas y beneficios que NEXOS Card tiene para ti.
                    </p>
                </header>

                <h3 class="ug-welcome-title" v-reveal>
                    <i class="fas fa-glass-cheers" aria-hidden="true"></i> Bienvenido. Estás a pocos pasos de crear tu tarjeta digital interactiva.
                </h3>
                <div class="ug-welcome">
                    <article v-for="(w, i) in welcome" :key="w.key" v-reveal="(i % 2) * 100" class="lt-card lt-spot ug-card" @pointermove="spotlight">
                        <span class="lt-icon-tile"><i :class="w.icon" aria-hidden="true"></i></span>
                        <h3>{{ w.title }}</h3>
                        <p v-if="w.text">{{ w.text }}</p>
                        <p v-if="w.highlight" class="ug-time lt-gradient-text">{{ w.highlight }}</p>
                        <p v-if="w.note" class="ug-muted-note">{{ w.note }}</p>
                        <ul v-if="w.items" class="ug-checklist">
                            <li v-for="it in w.items" :key="it"><i class="fas fa-check-circle" aria-hidden="true"></i> {{ it }}</li>
                        </ul>
                    </article>
                </div>

                <!-- Índice de los 8 pasos -->
                <nav class="ug-index" aria-label="Los 8 pasos" v-reveal>
                    <a v-for="(s, i) in steps" :key="s.id" :href="`#${s.id}`" class="ug-index-item" @click.prevent="goTo(s.id)">
                        <span class="ug-index-num">0{{ i + 1 }}</span>
                        <span>{{ s.title }} {{ s.highlight }}</span>
                    </a>
                </nav>

                <div class="lt-center" v-reveal>
                    <a href="#paso-1" class="lt-btn lt-btn-primary lt-btn-lg" @click.prevent="goTo('paso-1')">
                        <i class="fas fa-rocket" aria-hidden="true"></i> ¡Comencemos!
                    </a>
                </div>
            </div>
        </section>

        <!-- ===================== Los 8 pasos ===================== -->
        <section
            v-for="(s, i) in steps"
            :id="s.id"
            :key="s.id"
            class="lt-section ug-step"
            :class="{ 'lt-section-alt': i % 2 === 1 }"
        >
            <div class="lt-container">
                <header class="ug-step-head" v-reveal>
                    <span class="ug-step-badge">Paso 0{{ i + 1 }} <span>de 08</span></span>
                    <h2 class="lt-title lt-left">{{ s.title }} <span class="lt-gradient-text">{{ s.highlight }}</span></h2>
                    <p class="lt-lead ug-step-intro">{{ s.intro }}</p>
                </header>

                <!-- Tarjetas: qué hacer, resultado, consejo... -->
                <div v-if="s.cards" class="ug-cards">
                    <article
                        v-for="(c, k) in s.cards"
                        :key="c.title"
                        v-reveal="k * 90"
                        class="lt-card lt-spot ug-card"
                        :class="`ug-tone-${c.tone}`"
                        @pointermove="spotlight"
                    >
                        <span class="ug-tone-icon"><i :class="c.icon" aria-hidden="true"></i></span>
                        <h3>{{ c.title }}</h3>
                        <p v-if="c.text">{{ c.text }}</p>
                        <ul v-if="c.items" class="ug-checklist">
                            <li v-for="it in c.items" :key="it"><i class="fas fa-check-circle" aria-hidden="true"></i> {{ it }}</li>
                        </ul>
                    </article>
                </div>

                <div v-if="s.action" class="ug-action" v-reveal>
                    <router-link :to="s.action.to" class="lt-btn lt-btn-primary lt-btn-lg">
                        <i :class="s.action.icon" aria-hidden="true"></i> {{ s.action.label }}
                    </router-link>
                    <span>Para nuevos usuarios que comienzan desde cero.</span>
                </div>

                <!-- Campos con icono junto a la captura -->
                <div v-if="s.fields" class="ug-split">
                    <div class="ug-fields-col">
                        <ul class="ug-fields">
                            <li v-for="(f, k) in s.fields" :key="f.title" v-reveal="k * 70" :class="`ug-tone-${f.tone}`">
                                <span class="ug-tone-icon"><i :class="f.icon" aria-hidden="true"></i></span>
                                <div>
                                    <h3>{{ f.title }}</h3>
                                    <p>{{ f.text }}</p>
                                </div>
                            </li>
                        </ul>
                        <div v-if="s.note" class="lt-card ug-note" v-reveal>
                            <span class="ug-tone-icon"><i :class="s.note.icon" aria-hidden="true"></i></span>
                            <div>
                                <h3>{{ s.note.title }}</h3>
                                <p>
                                    {{ s.note.text }}
                                    <a :href="`#${s.note.to}`" class="lt-link" @click.prevent="goTo(s.note.to)">Ir al paso 3</a>
                                </p>
                            </div>
                        </div>
                    </div>
                    <figure class="ug-shot" v-reveal="120">
                        <button type="button" class="ug-shot-btn" :aria-label="`Ampliar: ${s.image.alt}`" @click="zoom(s.image)">
                            <GuideClip :name="s.image.name" :alt="s.image.alt" />
                        </button>
                        <figcaption><i class="fas fa-search-plus" aria-hidden="true"></i> Toca el video para verlo en grande</figcaption>
                    </figure>
                </div>

                <!-- Captura a lo ancho -->
                <figure v-else-if="s.image" class="ug-shot ug-shot-wide" v-reveal>
                    <button type="button" class="ug-shot-btn" :aria-label="`Ampliar: ${s.image.alt}`" @click="zoom(s.image)">
                        <GuideClip :name="s.image.name" :alt="s.image.alt" />
                    </button>
                    <figcaption>
                        <i class="fas fa-search-plus" aria-hidden="true"></i>
                        {{ s.caption || 'Toca el video para verlo en grande' }}
                    </figcaption>
                </figure>

                <!-- Paso 7: comprobaciones y qué hacer ahora -->
                <template v-if="s.checks">
                    <h3 class="ug-subtitle" v-reveal>{{ s.checksTitle }}</h3>
                    <div class="ug-checks">
                        <article v-for="(c, k) in s.checks" :key="c.title" v-reveal="k * 80" class="lt-card lt-spot ug-check" :class="`ug-tone-${c.tone}`" @pointermove="spotlight">
                            <span class="ug-tone-icon ug-tone-round"><i :class="c.icon" aria-hidden="true"></i></span>
                            <h3>{{ c.title }}</h3>
                            <p>{{ c.text }}</p>
                        </article>
                    </div>
                    <div class="ug-next">
                        <div class="lt-card ug-next-card" v-reveal>
                            <h3>{{ s.nextTitle }}</h3>
                            <ol>
                                <li v-for="n in s.next" :key="n">{{ n }}</li>
                            </ol>
                        </div>
                        <div class="ug-next-visual" v-reveal="120">
                            <span class="lt-ring lt-ring-soft" aria-hidden="true"></span>
                            <img :src="landingImg('phone-contact')" alt="Ejemplo de tarjeta Nexos Card" loading="lazy">
                        </div>
                    </div>
                </template>

                <!-- Paso 8: mejorar el plan -->
                <div v-if="i === steps.length - 1" class="ug-upgrade" v-reveal>
                    <div class="ug-upgrade-main">
                        <span class="lt-icon-tile"><i class="far fa-gem" aria-hidden="true"></i></span>
                        <div>
                            <h3>{{ upgrade.title }}</h3>
                            <p>{{ upgrade.text }}</p>
                        </div>
                    </div>
                    <div class="ug-upgrade-team">
                        <i class="fas fa-users" aria-hidden="true"></i>
                        <div>
                            <h3>{{ upgrade.teamTitle }}</h3>
                            <p>{{ upgrade.teamText }}</p>
                        </div>
                    </div>
                    <div class="ug-upgrade-ctas">
                        <a :href="whatsappUrl" target="_blank" rel="noopener" class="lt-btn lt-btn-whatsapp">
                            <i class="fab fa-whatsapp" aria-hidden="true"></i> Solicita más información
                        </a>
                        <router-link to="/planes" class="lt-btn lt-btn-primary">
                            Ver planes y mejorar <i class="fas fa-arrow-right" aria-hidden="true"></i>
                        </router-link>
                    </div>
                </div>

                <!-- Siguiente paso -->
                <div class="ug-continue" v-reveal>
                    <a
                        v-if="i < steps.length - 1"
                        :href="`#${steps[i + 1].id}`"
                        class="ug-continue-btn"
                        @click.prevent="goTo(steps[i + 1].id)"
                    >
                        <span class="ug-continue-orb"><i class="fas fa-rocket" aria-hidden="true"></i></span>
                        <span>{{ s.closing || '¡Vamos por el siguiente paso!' }}</span>
                        <i class="fas fa-arrow-down" aria-hidden="true"></i>
                    </a>
                    <router-link v-else to="/planes" class="ug-continue-btn">
                        <span class="ug-continue-orb"><i class="fas fa-rocket" aria-hidden="true"></i></span>
                        <span>Actualiza al plan anual y crece</span>
                        <i class="fas fa-arrow-right" aria-hidden="true"></i>
                    </router-link>
                </div>
            </div>
        </section>

        <!-- ===================== Ayuda ===================== -->
        <section id="ayuda" class="lt-section lt-section-tight">
            <div class="lt-container">
                <div class="lt-join ug-help" v-reveal>
                    <div class="lt-join-mesh" aria-hidden="true"></div>
                    <div class="lt-join-copy">
                        <h2 class="lt-title lt-left">¿Te quedó alguna duda?</h2>
                        <p class="lt-lead">Escríbenos y te ayudamos a dejar tu tarjeta lista.</p>
                        <div class="ug-help-ctas">
                            <a :href="whatsappUrl" target="_blank" rel="noopener" class="lt-btn lt-btn-whatsapp lt-btn-lg">
                                <i class="fab fa-whatsapp" aria-hidden="true"></i> Escríbenos por WhatsApp
                            </a>
                            <a :href="`mailto:${email}`" class="lt-btn lt-btn-light lt-btn-lg">
                                <i class="fas fa-envelope" aria-hidden="true"></i> {{ email }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ===================== Footer ===================== -->
        <footer class="lt-footer">
            <div class="lt-container">
                <div class="lt-footer-top">
                    <div>
                        <div class="lt-footer-brand">
                            <img :src="landingImg('logo-mark')" alt="">
                            <span>Nexos Card</span>
                        </div>
                        <p>La tarjeta digital que conecta tu negocio.</p>
                    </div>
                    <SupportInfo class="lt-footer-support" />
                    <div class="lt-footer-social">
                        <span>Síguenos</span>
                        <a v-for="so in socials" :key="so.label" :href="so.href" target="_blank" rel="noopener" :aria-label="so.label">
                            <i :class="so.icon" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
                <div class="lt-footer-bottom">
                    <span>© {{ year }} Nexos Card - Todos los derechos reservados.</span>
                    <span class="lt-footer-legal">
                        <router-link to="/inicio">Inicio</router-link>
                        <router-link to="/politica-privacidad">Política de Privacidad</router-link>
                        <router-link to="/terminos-condiciones">Términos y Condiciones</router-link>
                    </span>
                </div>
            </div>
            <div class="lt-footer-word" aria-hidden="true">NEXOS</div>
        </footer>

        <!-- Imagen ampliada. Va en la raíz de la página: dentro de un bloque con transform
             (las animaciones de aparición) el position: fixed se ancla al bloque y no a la ventana. -->
        <div v-if="zoomed" class="ug-lightbox" role="dialog" aria-modal="true" :aria-label="zoomed.alt" @click.self="zoomed = null">
            <button type="button" class="ug-lightbox-close" aria-label="Cerrar" @click="zoomed = null">
                <i class="fas fa-times" aria-hidden="true"></i>
            </button>
            <video :src="`/images/guia/${zoomed.name}.mp4`" :poster="guideImg(zoomed.name)" :aria-label="zoomed.alt"
                   autoplay muted loop playsinline controls></video>
        </div>
    </div>
</template>

<script>
import { useAuth } from '@/stores/auth';
import { FALLBACK, socials, landingImage, fetchLandingData, loadFonts } from './landing/content.js';
import SupportInfo from '@/components/shared/SupportInfo.vue';
import { guideImage, heroChips, missionOne, buildWelcome, buildSteps, upgrade } from './guide/content.js';
import GuideClip from './guide/GuideClip.vue';

export default {
    name: 'UserGuide',

    components: { GuideClip, SupportInfo },

    directives: {
        // Aparición al entrar en pantalla, igual que en la página de inicio.
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
            heroChips,
            missionOne,
            upgrade,
            socials,
            trialDays: FALLBACK.trialDays,
            whatsapp:  FALLBACK.whatsapp,
            email:     FALLBACK.email,
            navLinks: [
                { id: 'mision-1',         label: 'Misión 1' },
                { id: 'antes-de-empezar', label: 'Antes de empezar' },
                { id: 'paso-1',           label: 'Los 8 pasos' },
                { id: 'paso-8',           label: 'Mi Plan' },
                { id: 'ayuda',            label: 'Ayuda' },
            ],
            zoomed:         null,
            motionOk:       true,
            scrolled:       false,
            scrollProgress: 0,
        };
    },

    computed: {
        year() {
            return new Date().getFullYear();
        },
        isAuthenticated() {
            return useAuth().isAuthenticated.value;
        },
        whatsappUrl() {
            return `https://wa.me/${this.whatsapp}`;
        },
        welcome() {
            return buildWelcome(this.trialDays);
        },
        steps() {
            return buildSteps(this.trialDays);
        },
    },

    watch: {
        zoomed(value) {
            document.body.style.overflow = value ? 'hidden' : '';
        },
    },

    created() {
        this.motionOk = !window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        this.frame = null;
        this.pendingReveal = new Set();

        this.revealObserver = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) this.reveal(entry.target);
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
    },

    async mounted() {
        document.title = 'Guía de primeros pasos | NEXOS Card';
        loadFonts('lt-landing-fonts', 'https://fonts.googleapis.com/css2?family=Rammetto+One&display=swap');

        window.addEventListener('scroll', this.onScroll, { passive: true });
        window.addEventListener('keydown', this.onKey);
        this.onScroll();

        // Enlace directo a un paso (/guia-usuario#paso-4): se baja cuando la página ya está pintada.
        if (this.$route.hash) {
            this.$nextTick(() => this.goTo(this.$route.hash.slice(1), false));
        }

        const data = await fetchLandingData();
        if (data) {
            this.trialDays = data.trialDays;
            this.whatsapp  = data.whatsapp;
            this.email     = data.email;
        }
    },

    beforeUnmount() {
        window.removeEventListener('scroll', this.onScroll);
        window.removeEventListener('keydown', this.onKey);
        document.body.style.overflow = '';
        this.revealObserver.disconnect();
        cancelAnimationFrame(this.frame);
    },

    methods: {
        guideImg: guideImage,
        landingImg: landingImage,

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

        // Con un salto del índice la página cruza secciones enteras sin que el observer las
        // reporte: todo lo que ya quedó por encima del borde inferior se muestra.
        revealPassed() {
            const limit = window.innerHeight;
            const passed = [...this.pendingReveal].filter((el) => el.getBoundingClientRect().top < limit);
            passed.forEach((el) => this.reveal(el));
        },

        goTo(id, smooth = true) {
            const el = document.getElementById(id);
            if (!el) return;
            el.scrollIntoView({ behavior: smooth && this.motionOk ? 'smooth' : 'auto' });
            history.replaceState(history.state, '', `#${id}`);
        },

        onScroll() {
            if (this.frame) return;
            this.frame = requestAnimationFrame(() => {
                this.frame = null;
                const max = document.documentElement.scrollHeight - window.innerHeight;
                this.scrollProgress = max > 0 ? Math.min(window.scrollY / max, 1) : 0;
                this.scrolled = window.scrollY > 24;
                if (this.pendingReveal.size) this.revealPassed();
            });
        },

        onKey(e) {
            if (e.key === 'Escape') this.zoomed = null;
        },

        zoom(image) {
            this.zoomed = image;
        },

        spotlight(e) {
            const el = e.currentTarget;
            const r = el.getBoundingClientRect();
            el.style.setProperty('--mx', `${e.clientX - r.left}px`);
            el.style.setProperty('--my', `${e.clientY - r.top}px`);
        },
    },
};
</script>

<style scoped>
/* =====================================================================
   Base compartida con la página de inicio (Landing.vue): mismos tokens y
   las mismas piezas (nav, aurora, tarjetas de vidrio, botones, footer).
   ===================================================================== */
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
    padding: 6rem 0;
    scroll-margin-top: 0;
}

.lt-section-alt {
    background: var(--lt-bg-alt);
}

.lt-section-tight {
    padding: 3rem 0 5rem;
}

.lt-center {
    text-align: center;
}

.lt-head {
    max-width: 780px;
    margin: 0 auto 3.5rem;
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
    font-size: clamp(2rem, 4.2vw, 3.2rem);
    line-height: 1.08;
    letter-spacing: -0.02em;
    color: #ffffff;
}

.lt-left {
    text-align: left;
}

.lt-sub {
    margin: 1.25rem auto 0;
    max-width: 660px;
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

/* ---------- Botones ---------- */
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

.lt-btn-primary {
    background: var(--lt-brand);
    background-size: 160% auto;
    color: #ffffff;
    box-shadow: 0 10px 30px -8px rgba(217, 70, 239, 0.6);
}

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

/* ---------- Progreso y navegación ---------- */
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

.lt-nav-actions {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* ---------- Aurora del hero ---------- */
.lt-hero {
    position: relative;
    display: flex;
    align-items: center;
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

.lt-halo {
    position: absolute;
    inset: 10% 15%;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(217, 70, 239, 0.45), transparent 65%);
    filter: blur(20px);
}

.lt-motion .lt-intro {
    opacity: 0;
    animation: lt-rise 0.9s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
    animation-delay: calc(var(--i) * 90ms + 150ms);
}

/* ---------- Tarjetas de vidrio ---------- */
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

.lt-ring {
    position: absolute;
    width: 80%;
    aspect-ratio: 1;
    border-radius: 50%;
    border: 1px dashed rgba(240, 171, 252, 0.35);
}

.lt-ring-soft {
    border-style: solid;
    border-color: rgba(255, 255, 255, 0.08);
}

/* ---------- Bloque de ayuda (mismo que «Únete» de la página de inicio) ---------- */
.lt-join {
    position: relative;
    display: grid;
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

/* ---------- Footer ---------- */
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

.lt-footer-support {
    color: var(--lt-muted);
    font-size: 0.95rem;
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

/* ---------- Aparición al hacer scroll ---------- */
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

.lt-motion .lt-reveal.is-in.lt-spot:hover {
    transform: translateY(-4px);
    transition-delay: 0ms;
}

/* =====================================================================
   Piezas propias de la guía
   ===================================================================== */

/* ---------- Tonos (iconos de colores de la guía original) ---------- */
.ug-tone-violet  { --tone: #8b5cf6; }
.ug-tone-fuchsia { --tone: #d946ef; }
.ug-tone-amber   { --tone: #f59e0b; }
.ug-tone-green   { --tone: #22c55e; }
.ug-tone-rose    { --tone: #fb7185; }
.ug-tone-cyan    { --tone: #38bdf8; }
.ug-tone-whatsapp { --tone: #25d366; }

.ug-tone-icon {
    flex-shrink: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 52px;
    height: 52px;
    border-radius: 16px;
    background: color-mix(in srgb, var(--tone, #8b5cf6) 18%, transparent);
    border: 1px solid color-mix(in srgb, var(--tone, #8b5cf6) 45%, transparent);
    color: var(--tone, #8b5cf6);
    font-size: 1.3rem;
}

.ug-tone-round {
    border-radius: 50%;
}

/* ---------- Portada ---------- */
.ug-hero {
    padding: 8rem 0 5rem;
}

.ug-hero-inner {
    display: grid;
    grid-template-columns: 1.05fr 0.95fr;
    align-items: center;
    gap: 3rem;
}

.ug-hero-title {
    margin: 1.4rem 0 0;
    font-family: 'Outfit', 'Inter', sans-serif;
    font-weight: 800;
    font-size: clamp(2.6rem, 5.4vw, 4.6rem);
    line-height: 1.02;
    letter-spacing: -0.03em;
    color: #ffffff;
}

.ug-hero-lead {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin: 1.25rem 0 0;
    font-size: 1.3rem;
    color: var(--lt-text);
}

.ug-hero-lead i {
    color: #f0abfc;
}

.ug-hero-chips {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
    margin: 2rem 0 0;
    padding: 0;
    list-style: none;
}

.ug-hero-chips li {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    padding: 0.5rem 1rem 0.5rem 0.5rem;
    border: 1px solid var(--lt-line);
    border-radius: 999px;
    background: var(--lt-surface);
    color: var(--lt-muted);
    font-size: 0.92rem;
    backdrop-filter: blur(10px);
}

.ug-hero-chips strong {
    color: #ffffff;
    font-weight: 600;
}

.ug-chip-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: var(--lt-brand);
    color: #ffffff;
    font-size: 0.85rem;
}

.ug-login-box {
    margin-top: 2.25rem;
    padding: 1.6rem 1.75rem;
    border: 1px solid rgba(217, 70, 239, 0.35);
    border-radius: 24px;
    background: linear-gradient(160deg, rgba(124, 58, 237, 0.22), rgba(217, 70, 239, 0.08));
    backdrop-filter: blur(12px);
    max-width: 520px;
}

.ug-login-box p {
    margin: 0 0 0.35rem;
    color: var(--lt-muted);
}

.ug-login-box .ug-login-q {
    color: #ffffff;
    font-weight: 700;
    font-size: 1.1rem;
}

.ug-login-box .lt-btn {
    margin: 0.9rem 0 1rem;
}

.ug-login-small {
    font-size: 0.9rem;
}

.ug-hero-visual {
    position: relative;
    display: flex;
    justify-content: center;
}

.ug-hero-phone {
    position: relative;
    width: 125%;
    max-width: 760px;
    height: auto;
    filter: drop-shadow(0 30px 50px rgba(0, 0, 0, 0.55));
}

.lt-motion .ug-hero-phone {
    animation: lt-float 7s ease-in-out infinite;
}

/* ---------- Capturas del panel ---------- */
.ug-shot {
    margin: 0;
}

.ug-shot-btn {
    display: block;
    width: 100%;
    padding: 0.5rem;
    border: 1px solid var(--lt-line);
    border-radius: 22px;
    background: linear-gradient(160deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.03));
    box-shadow: 0 30px 60px -30px rgba(0, 0, 0, 0.8), 0 0 0 1px rgba(217, 70, 239, 0.08);
    cursor: zoom-in;
    transition: transform 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease;
}

.ug-shot-btn:hover {
    transform: translateY(-3px);
    border-color: rgba(217, 70, 239, 0.45);
    box-shadow: 0 30px 60px -28px rgba(217, 70, 239, 0.45);
}

.ug-shot-btn :deep(video) {
    pointer-events: none;
}

.ug-shot figcaption {
    margin-top: 0.75rem;
    font-size: 0.88rem;
    color: var(--lt-muted);
    text-align: center;
}

.ug-shot figcaption i {
    color: #f0abfc;
    margin-right: 0.3rem;
}

.ug-shot-wide {
    max-width: 1040px;
    margin: 3rem auto 0;
}

/* ---------- Misión 1 ---------- */
.ug-num {
    flex-shrink: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: var(--lt-brand);
    color: #ffffff;
    font-family: 'Outfit', 'Inter', sans-serif;
    font-weight: 800;
    font-size: 1.2rem;
    box-shadow: 0 10px 24px -8px rgba(217, 70, 239, 0.6);
}

.ug-mission-intro {
    display: flex;
    align-items: center;
    gap: 1rem;
    max-width: 1040px;
    margin: 0 auto 1.5rem;
    font-size: 1.15rem;
    color: #ffffff;
}

.ug-mission-intro .ug-num {
    background: var(--lt-surface-strong);
    border: 1px solid var(--lt-line);
    box-shadow: none;
    color: #f0abfc;
    font-size: 1rem;
}

#mision-1 .ug-shot {
    max-width: 1040px;
    margin-left: auto;
    margin-right: auto;
}

.ug-mission-after {
    max-width: 1040px;
    margin: 1.5rem auto 2.5rem;
    color: var(--lt-muted);
    font-size: 1.05rem;
}

.ug-mission-steps {
    display: grid;
    gap: 2.5rem;
    max-width: 1040px;
    margin: 0 auto;
    padding: 0;
    list-style: none;
}

.ug-mission-text {
    display: flex;
    gap: 1rem;
    margin-bottom: 1.25rem;
}

.ug-mission-text h3 {
    margin: 0.35rem 0 0.35rem;
    font-family: 'Outfit', 'Inter', sans-serif;
    font-size: 1.4rem;
    font-weight: 700;
    color: #ffffff;
}

.ug-mission-text p {
    margin: 0;
    color: var(--lt-muted);
    line-height: 1.6;
}

.ug-congrats {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    width: fit-content;
    margin: 3.5rem auto 0;
    padding: 1.1rem 2.25rem;
    border: 1px solid rgba(255, 255, 255, 0.18);
    border-radius: 22px;
    background: linear-gradient(120deg, rgba(124, 58, 237, 0.35), rgba(217, 70, 239, 0.25));
    text-align: center;
    font-size: 1.4rem;
}

.ug-congrats > i {
    color: #fde68a;
    font-size: 1.1rem;
}

.ug-congrats div {
    display: flex;
    flex-direction: column;
}

.ug-congrats strong {
    font-family: 'Outfit', 'Inter', sans-serif;
    font-size: 1.6rem;
    color: #ffffff;
}

.ug-congrats div span {
    color: var(--lt-text);
    font-size: 1.15rem;
}

/* ---------- Bienvenida ---------- */
.ug-welcome-title i {
    color: #f0abfc;
    margin-right: 0.35rem;
}

.ug-welcome-title {
    margin: 0 0 1.75rem;
    text-align: center;
    font-family: 'Outfit', 'Inter', sans-serif;
    font-size: 1.35rem;
    font-weight: 700;
    color: #ffffff;
}

.ug-welcome {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.1rem;
}

.ug-card {
    display: flex;
    flex-direction: column;
    gap: 0.9rem;
    padding: 1.6rem;
}

.ug-card h3 {
    margin: 0.2rem 0 0;
    font-family: 'Outfit', 'Inter', sans-serif;
    font-size: 1.2rem;
    font-weight: 700;
    color: #ffffff;
}

.ug-card p {
    margin: 0;
    font-size: 0.97rem;
    line-height: 1.6;
    color: var(--lt-muted);
}

.ug-time {
    font-family: 'Outfit', 'Inter', sans-serif;
    font-size: 1.7rem !important;
    font-weight: 800;
    line-height: 1.2 !important;
}

.ug-muted-note {
    font-size: 0.9rem !important;
}

.ug-checklist {
    display: grid;
    gap: 0.55rem;
    margin: 0;
    padding: 0;
    list-style: none;
}

.ug-checklist li {
    display: flex;
    gap: 0.6rem;
    font-size: 0.95rem;
    line-height: 1.5;
    color: var(--lt-text);
}

.ug-checklist i {
    margin-top: 0.2rem;
    color: #4ade80;
}

/* ---------- Índice ---------- */
.ug-index {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 0.75rem;
    margin: 3.5rem 0 3rem;
}

.ug-index-item {
    display: flex;
    align-items: center;
    gap: 0.85rem;
    padding: 0.95rem 1.1rem;
    border: 1px solid var(--lt-line);
    border-radius: 18px;
    background: var(--lt-surface);
    color: var(--lt-text);
    font-weight: 600;
    font-size: 0.95rem;
    text-decoration: none;
    transition: all 0.25s ease;
}

.ug-index-item:hover {
    color: #ffffff;
    border-color: rgba(217, 70, 239, 0.45);
    background: var(--lt-surface-strong);
    transform: translateY(-2px);
}

.ug-index-num {
    font-family: 'Outfit', 'Inter', sans-serif;
    font-size: 1.5rem;
    font-weight: 800;
    color: transparent;
    -webkit-text-stroke: 1.2px rgba(240, 171, 252, 0.8);
}

/* ---------- Pasos ---------- */
.ug-step-head {
    max-width: 820px;
    margin-bottom: 2.5rem;
}

.ug-step-badge {
    display: inline-flex;
    align-items: baseline;
    gap: 0.4rem;
    padding: 0.45rem 1.1rem;
    border-radius: 999px;
    background: var(--lt-brand);
    color: #ffffff;
    font-family: 'Outfit', 'Inter', sans-serif;
    font-weight: 800;
    font-size: 0.95rem;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    box-shadow: 0 10px 24px -8px rgba(217, 70, 239, 0.6);
}

.ug-step-badge span {
    font-weight: 600;
    opacity: 0.8;
    font-size: 0.8rem;
}

.ug-step-intro {
    margin-bottom: 0;
}

.ug-cards {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.1rem;
}

.ug-action {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem 1.25rem;
    margin-top: 2rem;
    color: var(--lt-muted);
}

.ug-split {
    display: grid;
    grid-template-columns: 0.8fr 1.2fr;
    align-items: start;
    gap: 3rem;
}

.ug-fields {
    display: grid;
    gap: 0.85rem;
    margin: 0;
    padding: 0;
    list-style: none;
}

.ug-fields li {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem 1.1rem;
    border: 1px solid var(--lt-line);
    border-radius: 20px;
    background: var(--lt-surface);
}

.ug-fields h3,
.ug-note h3 {
    margin: 0;
    font-family: 'Outfit', 'Inter', sans-serif;
    font-size: 1.05rem;
    font-weight: 700;
    color: var(--tone, #ffffff);
    text-transform: uppercase;
    letter-spacing: 0.04em;
}

.ug-fields p,
.ug-note p {
    margin: 0.2rem 0 0;
    font-size: 0.95rem;
    line-height: 1.5;
    color: var(--lt-muted);
}

.ug-note {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-top: 1.25rem;
    padding: 1.1rem 1.2rem;
    border-radius: 20px;
    border-color: rgba(56, 189, 248, 0.4);
    --tone: #38bdf8;
}

.ug-subtitle {
    margin: 0 0 1.5rem;
    font-family: 'Outfit', 'Inter', sans-serif;
    font-size: 1.4rem;
    font-weight: 700;
    background: var(--lt-brand-loop);
    background-size: 200% auto;
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
}

.ug-checks {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.1rem;
}

.ug-check {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.6rem;
    padding: 1.6rem 1.25rem;
    text-align: center;
}

.ug-check .ug-tone-icon {
    width: 64px;
    height: 64px;
    font-size: 1.7rem;
}

.ug-check h3 {
    margin: 0.3rem 0 0;
    font-family: 'Outfit', 'Inter', sans-serif;
    font-size: 1.2rem;
    color: #ffffff;
}

.ug-check p {
    margin: 0;
    font-size: 0.93rem;
    line-height: 1.5;
    color: var(--lt-muted);
}

.ug-next {
    display: grid;
    grid-template-columns: 1.2fr 0.8fr;
    align-items: center;
    gap: 3rem;
    margin-top: 3rem;
}

.ug-next-card {
    padding: 2rem 2.25rem;
    border-color: rgba(56, 189, 248, 0.35);
    background: linear-gradient(160deg, rgba(56, 189, 248, 0.14), rgba(255, 255, 255, 0.02));
}

.ug-next-card h3 {
    margin: 0 0 1.1rem;
    font-family: 'Outfit', 'Inter', sans-serif;
    font-size: 1.4rem;
    color: #ffffff;
}

.ug-next-card ol {
    display: grid;
    gap: 0.8rem;
    margin: 0;
    padding: 0;
    list-style: none;
    counter-reset: ug-next;
}

.ug-next-card li {
    display: flex;
    align-items: center;
    gap: 0.85rem;
    color: var(--lt-text);
    counter-increment: ug-next;
}

.ug-next-card li::before {
    content: counter(ug-next);
    flex-shrink: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: #22c55e;
    color: #052e16;
    font-weight: 800;
    font-size: 0.9rem;
}

.ug-next-visual {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
}

.ug-next-visual img {
    position: relative;
    max-width: 280px;
    filter: drop-shadow(0 30px 50px rgba(0, 0, 0, 0.55));
}

.lt-motion .ug-next-visual img {
    animation: lt-float 7s ease-in-out infinite;
}

/* ---------- Mejorar plan ---------- */
.ug-upgrade {
    display: grid;
    grid-template-columns: 1.3fr 1fr auto;
    align-items: center;
    gap: 2rem;
    margin-top: 3rem;
    padding: 2rem;
    border: 1px solid rgba(217, 70, 239, 0.35);
    border-radius: var(--lt-radius);
    background: linear-gradient(120deg, rgba(124, 58, 237, 0.22), rgba(217, 70, 239, 0.1));
}

.ug-upgrade-main,
.ug-upgrade-team {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.ug-upgrade h3 {
    margin: 0 0 0.35rem;
    font-family: 'Outfit', 'Inter', sans-serif;
    font-size: 1.2rem;
    color: #ffffff;
}

.ug-upgrade p {
    margin: 0;
    font-size: 0.95rem;
    line-height: 1.55;
    color: var(--lt-muted);
}

.ug-upgrade-team > i {
    flex-shrink: 0;
    font-size: 1.8rem;
    color: #fdba74;
}

.ug-upgrade-ctas {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

/* ---------- Siguiente paso ---------- */
.ug-continue {
    display: flex;
    justify-content: center;
    margin-top: 3.5rem;
}

.ug-continue-btn {
    display: inline-flex;
    align-items: center;
    gap: 1.1rem;
    padding: 0.5rem 1.5rem 0.5rem 0.5rem;
    border: 1px solid rgba(217, 70, 239, 0.35);
    border-radius: 999px;
    background: linear-gradient(120deg, #2e1065, #4c1d95);
    color: #ffffff;
    font-family: 'Outfit', 'Inter', sans-serif;
    font-size: 1.25rem;
    font-weight: 700;
    text-decoration: none;
    box-shadow: 0 20px 40px -20px rgba(217, 70, 239, 0.6);
    transition: transform 0.25s ease, box-shadow 0.25s ease;
}

.ug-continue-btn:hover {
    color: #ffffff;
    transform: translateY(-3px);
    box-shadow: 0 26px 50px -20px rgba(217, 70, 239, 0.8);
}

.ug-continue-orb {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 54px;
    height: 54px;
    border-radius: 50%;
    background: var(--lt-brand);
    font-size: 1.3rem;
}

.ug-continue-btn > i {
    color: #f0abfc;
}

.lt-motion .ug-continue-btn > i.fa-arrow-down {
    animation: lt-bob 2.2s ease-in-out infinite;
}

/* ---------- Ayuda ---------- */
.ug-help {
    grid-template-columns: 1fr;
}

.ug-help-ctas {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
}

/* ---------- Imagen ampliada ---------- */
.ug-lightbox {
    position: fixed;
    inset: 0;
    z-index: 100;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 4rem 1rem 1rem;
    background: rgba(4, 5, 15, 0.92);
    backdrop-filter: blur(6px);
    overflow: auto;
    cursor: zoom-out;
}

.ug-lightbox video {
    max-width: min(1400px, 100%);
    max-height: calc(100vh - 5rem);
    border-radius: 14px;
    box-shadow: 0 30px 80px rgba(0, 0, 0, 0.7);
    cursor: default;
}

.ug-lightbox-close {
    position: fixed;
    top: 1rem;
    right: 1rem;
    width: 46px;
    height: 46px;
    border: 1px solid var(--lt-line);
    border-radius: 50%;
    background: var(--lt-surface-strong);
    color: #ffffff;
    font-size: 1.2rem;
    cursor: pointer;
}

/* ---------- Animaciones ---------- */
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

@keyframes lt-float {
    0%, 100% { translate: 0 0; }
    50%      { translate: 0 -14px; }
}

@keyframes lt-bob {
    0%, 100% { translate: 0 0; }
    50%      { translate: 0 6px; }
}

@keyframes lt-mesh {
    to { transform: scale(1.15) translate(3%, -3%); }
}

/* ---------- Tablet ---------- */
@media (max-width: 1080px) {
    .lt-nav-links {
        display: none;
    }

    .lt-nav-actions {
        margin-left: auto;
    }

    .ug-welcome,
    .ug-index,
    .ug-checks {
        grid-template-columns: repeat(2, 1fr);
    }

    .ug-upgrade {
        grid-template-columns: 1fr;
    }

    .ug-upgrade-ctas {
        flex-direction: row;
        flex-wrap: wrap;
    }
}

/* ---------- Móvil ---------- */
@media (max-width: 860px) {
    .lt-section {
        padding: 4.5rem 0;
    }

    .lt-head {
        margin-bottom: 2.5rem;
    }

    .lt-eyebrow {
        letter-spacing: 0.08em;
    }

    .lt-nav {
        gap: 0.75rem;
        padding-left: 0.75rem;
    }

    .lt-nav-brand span {
        font-size: 0.95rem;
    }

    .lt-nav-actions .lt-btn-sm {
        padding: 0.5rem 0.8rem;
        font-size: 0.8rem;
    }

    .ug-hero {
        padding: 6.5rem 0 4rem;
    }

    .ug-hero-inner,
    .ug-split,
    .ug-next {
        grid-template-columns: 1fr;
    }

    .ug-hero-visual {
        order: -1;
    }

    .ug-hero-phone {
        width: 100%;
        max-width: 460px;
    }

    .ug-cards {
        grid-template-columns: 1fr;
    }

    .ug-split {
        gap: 2rem;
    }

    .ug-next-visual {
        display: none;
    }

    .lt-join {
        padding: 2.5rem 1.5rem;
        border-radius: 30px;
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

@media (max-width: 480px) {
    .lt-nav-brand span {
        display: none;
    }

    .ug-hero-chips li {
        font-size: 0.85rem;
    }

    .ug-login-box {
        padding: 1.3rem;
    }

    .ug-welcome,
    .ug-index,
    .ug-checks {
        grid-template-columns: 1fr;
    }

    .ug-congrats {
        padding: 1rem 1.25rem;
    }

    .ug-congrats strong {
        font-size: 1.3rem;
    }

    .ug-continue-btn {
        font-size: 1.02rem;
        gap: 0.8rem;
        padding-right: 1.1rem;
    }

    .ug-continue-orb {
        width: 46px;
        height: 46px;
        font-size: 1.1rem;
    }

    .ug-help-ctas .lt-btn {
        width: 100%;
        white-space: normal;
    }
}
</style>

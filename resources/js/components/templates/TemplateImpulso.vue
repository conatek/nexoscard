<template>
    <div class="card-template impulso" :style="containerStyle">

        <!-- ============ LUCES DE AMBIENTE ============ -->
        <!-- Van detrás del cristal: sin ellas el backdrop-filter no tiene nada que
             desenfocar sobre un fondo plano y el efecto no se percibe. -->
        <template v-if="glassEnabled && glassLights">
            <div class="imp-ambient imp-ambient-1" :style="ambient1Style"></div>
            <div class="imp-ambient imp-ambient-2" :style="ambient2Style"></div>
            <div class="imp-ambient imp-ambient-3" :style="ambient3Style"></div>
        </template>

        <!-- ============ LÁMINA DE CRISTAL ============ -->
        <div class="imp-panel" :class="{ 'imp-panel-glass': glassEnabled }" :style="glassPanelStyle">

        <!-- ============ CABECERA ADAPTATIVA ============ -->
        <header class="imp-header">
            <!-- A) Solo empresa: logo grande centrado -->
            <template v-if="headerLayout === 'solo-empresa'">
                <div class="imp-logo-wrap imp-logo-lg">
                    <img v-if="company?.logo_path" :src="company.logo_path" :alt="company.name" class="imp-logo">
                    <h2 v-else class="imp-company-name">{{ company?.name }}</h2>
                </div>
            </template>

            <!-- B) Empresa + asesor (logo cuadrado/vertical/redondo) -->
            <template v-else-if="headerLayout === 'logo-asesor'">
                <div class="imp-logo-photo-row">
                    <div class="imp-logo-wrap imp-logo-md">
                        <img v-if="company?.logo_path" :src="company.logo_path" :alt="company.name" class="imp-logo">
                        <h2 v-else class="imp-company-name">{{ company?.name }}</h2>
                    </div>
                    <img v-if="card?.photo_path" :src="card.photo_path" :alt="fullName" class="imp-advisor-photo">
                </div>
                <h3 class="imp-advisor-name">
                    <span class="imp-name-first">{{ firstNamePart }}</span>
                    <span v-if="lastNamePart" class="imp-name-last">{{ ' ' + lastNamePart }}</span>
                </h3>
                <p v-if="card?.job_title" class="imp-advisor-role">{{ card.job_title }}</p>
            </template>

            <!-- C) Logo horizontal (banner) + asesor -->
            <template v-else-if="headerLayout === 'horizontal-asesor'">
                <div class="imp-logo-wrap imp-logo-banner">
                    <img v-if="company?.logo_path" :src="company.logo_path" :alt="company.name" class="imp-logo">
                    <h2 v-else class="imp-company-name">{{ company?.name }}</h2>
                </div>
                <img v-if="card?.photo_path" :src="card.photo_path" :alt="fullName" class="imp-advisor-photo imp-advisor-photo-center">
                <h3 class="imp-advisor-name">
                    <span class="imp-name-first">{{ firstNamePart }}</span>
                    <span v-if="lastNamePart" class="imp-name-last">{{ ' ' + lastNamePart }}</span>
                </h3>
                <p v-if="card?.job_title" class="imp-advisor-role">{{ card.job_title }}</p>
            </template>

            <!-- D) Solo asesor -->
            <template v-else>
                <img v-if="card?.photo_path" :src="card.photo_path" :alt="fullName" class="imp-advisor-photo imp-advisor-photo-center">
                <h3 class="imp-advisor-name">
                    <span class="imp-name-first">{{ firstNamePart }}</span>
                    <span v-if="lastNamePart" class="imp-name-last">{{ ' ' + lastNamePart }}</span>
                </h3>
                <p v-if="card?.job_title" class="imp-advisor-role">{{ card.job_title }}</p>
            </template>
        </header>

        <main class="imp-content">
            <!-- ============ CTA WHATSAPP ============ -->
            <a v-if="card?.whatsapp" :href="whatsappLink" target="_blank" rel="noopener" class="imp-wa-btn">
                <span class="imp-wa-icon"><i class="bi bi-whatsapp"></i></span>
                <span class="imp-wa-text">{{ waText }}</span>
                <span class="imp-wa-arrow"><i class="bi bi-chevron-right"></i></span>
            </a>

            <!-- ============ CONTACTO RÁPIDO ============ -->
            <div class="imp-contact-row">
                <a v-if="card?.mobile_phone" :href="`tel:${card.mobile_phone}`" class="imp-contact-btn imp-c-call">
                    <i class="bi bi-telephone-fill"></i><span>Llamar</span>
                </a>
                <a v-if="card?.email" :href="`mailto:${card.email}`" class="imp-contact-btn imp-c-mail">
                    <i class="bi bi-envelope-fill"></i><span>Email</span>
                </a>
                <a v-if="company?.web" :href="company.web" target="_blank" rel="noopener" class="imp-contact-btn imp-c-web">
                    <i class="bi bi-globe"></i><span>Website</span>
                </a>
            </div>

            <!-- ============ VIDEO ============ -->
            <div v-if="showVideo" class="imp-video-wrapper">
                <iframe
                    width="100%"
                    height="100%"
                    :src="`https://www.youtube.com/embed/${videoId}`"
                    title="Video promocional"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen>
                </iframe>
            </div>

            <!-- ============ GRILLA DE 6 TILES ============ -->
            <div class="imp-grid">
                <button
                    v-for="tile in rubro.tiles"
                    :key="tile.key"
                    class="imp-tile"
                    :class="tile.primary ? 'imp-tile-primary' : ''"
                    :style="glassSurface"
                    @click="handleTile(tile)"
                >
                    <i class="imp-tile-icon" :class="tileIconClass(tile)"></i>
                    <span class="imp-tile-label">{{ tile.label }}</span>
                </button>
            </div>

            <!-- ============ REDES SOCIALES ============ -->
            <div class="imp-social">
                <a v-if="company?.facebook" :href="company.facebook" target="_blank" rel="noopener" :style="glassSocialSurface" class="imp-social-circle">
                    <i class="bi bi-facebook"></i>
                </a>
                <a v-if="company?.instagram" :href="company.instagram" target="_blank" rel="noopener" :style="glassSocialSurface" class="imp-social-circle">
                    <i class="bi bi-instagram"></i>
                </a>
                <a v-if="company?.youtube" :href="company.youtube" target="_blank" rel="noopener" :style="glassSocialSurface" class="imp-social-circle">
                    <i class="bi bi-youtube"></i>
                </a>
                <a v-if="company?.tiktok" :href="company.tiktok" target="_blank" rel="noopener" :style="glassSocialSurface" class="imp-social-circle">
                    <i class="bi bi-tiktok"></i>
                </a>
                <a v-if="card?.linkedin" :href="card.linkedin" target="_blank" rel="noopener" :style="glassSocialSurface" class="imp-social-circle">
                    <i class="bi bi-linkedin"></i>
                </a>

                <!-- Compartir la tarjeta: mismo tratamiento visual que las redes -->
                <a
                    v-if="shareUrl"
                    :href="shareWhatsappLink"
                    target="_blank"
                    rel="noopener"
                    :style="glassSocialSurface"
                    class="imp-social-circle"
                    :title="shareLabel"
                    :aria-label="shareLabel"
                >
                    <i class="bi bi-share-fill"></i>
                </a>
            </div>
        </main>

        <!-- ============ PIE: DIRECCIÓN + CIUDAD/PAÍS ============ -->
        <footer class="imp-footer">
            <p v-if="company?.address" class="imp-address">{{ company.address }}</p>
            <p v-if="cityCountry" class="imp-city">{{ cityCountry }}</p>
        </footer>

        </div><!-- /.imp-panel -->
    </div>

    <!-- ============ MODAL: SOBRE NOSOTROS / PERFIL ============ -->
    <Teleport to="body">
    <div v-if="activeModal === 'about'" class="dm-overlay" @click.self="activeModal = null">
        <div class="dm-box">
            <div class="dm-header">
                <h3 class="dm-title">{{ aboutTile.label }}</h3>
                <button class="dm-close" @click="activeModal = null"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="dm-body">
                <div v-if="company?.logo_path" class="dm-about-logo">
                    <img :src="company.logo_path" :alt="company.name">
                </div>
                <h4 class="dm-about-name">{{ headerLayout === 'solo-asesor' || headerLayout === 'logo-asesor' || headerLayout === 'horizontal-asesor' ? (fullName || company?.name) : company?.name }}</h4>
                <div v-if="card?.description" class="dm-about-desc" v-html="card.description"></div>
                <div v-if="company?.address" class="dm-about-item">
                    <i class="bi bi-geo-alt-fill"></i>
                    <span>{{ company.address }}</span>
                </div>
                <div v-if="company?.web" class="dm-about-item">
                    <i class="bi bi-globe"></i>
                    <a :href="company.web" target="_blank" rel="noopener">{{ company.web }}</a>
                </div>
                <div v-if="company?.my_business" class="dm-about-item">
                    <i class="bi bi-google"></i>
                    <a :href="company.my_business" target="_blank" rel="noopener">Google My Business</a>
                </div>
                <div v-if="card?.mobile_phone" class="dm-about-item">
                    <i class="bi bi-telephone-fill"></i>
                    <a :href="`tel:${card.mobile_phone}`">{{ card.mobile_phone }}</a>
                </div>
                <div v-if="card?.email" class="dm-about-item">
                    <i class="bi bi-envelope-fill"></i>
                    <a :href="`mailto:${card.email}`">{{ card.email }}</a>
                </div>
            </div>
        </div>
    </div>
    </Teleport>

    <!-- ============ MODAL: SERVICIOS ============ -->
    <Teleport to="body">
    <div v-if="activeModal === 'services'" class="dm-overlay" @click.self="activeModal = null">
        <div class="dm-box">
            <div class="dm-header">
                <h3 class="dm-title">{{ servicesTile.label }}</h3>
                <button class="dm-close" @click="activeModal = null"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="dm-body">
                <div v-if="services.length === 0" class="dm-empty">
                    <i class="bi bi-journal-text"></i>
                    <p>No hay servicios disponibles</p>
                </div>
                <div v-for="service in services" :key="service.id" class="dm-service-item">
                    <img v-if="service.image_path" :src="service.image_path" :alt="service.name" class="dm-service-img">
                    <div class="dm-service-info">
                        <h5 class="dm-service-name">{{ service.name }}</h5>
                        <div v-if="service.description" class="dm-service-desc" v-html="service.description"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </Teleport>

    <!-- ============ MODAL: QR ============ -->
    <Teleport to="body">
    <div v-if="activeModal === 'qr'" class="dm-overlay" @click.self="activeModal = null">
        <div class="dm-box dm-box-sm">
            <div class="dm-header">
                <h3 class="dm-title">Código QR</h3>
                <button class="dm-close" @click="activeModal = null"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="dm-body dm-qr-body">
                <img :src="qrCodeUrl" alt="Código QR" class="dm-qr-img">
                <p class="dm-qr-hint">Escanea este código para acceder a la tarjeta</p>
            </div>
        </div>
    </div>
    </Teleport>
</template>

<script>
/*
 * Plantilla "Impulso"
 * Diseño mayormente fijo (ver notes/requests/new-template/plan). El cliente solo edita:
 * color de fondo, rubro, orientación del logo, video y los 2 enlaces de la grilla.
 * Colores, tamaños, iconos, radios y transparencias van codificados aquí (no en el schema).
 */

import { extractYouTubeId } from '@/utils/youtube.js'

// Presets por rubro: texto del CTA de WhatsApp + las 6 tiles de la grilla (orden fila a fila).
// keys: about | link1 | qr | services | link2 | maps
const RUBROS = {
    empresas: {
        cta: 'Asesoría personalizada',
        tiles: [
            { key: 'about',    label: 'Sobre nosotros',     icon: 'bi-people-fill',    primary: true },
            { key: 'link1',    label: 'Portafolio virtual', icon: 'bi-briefcase-fill' },
            { key: 'qr',       label: 'Código QR',          icon: 'bi-qr-code' },
            { key: 'services', label: 'Nuestros servicios', icon: 'bi-journal-text',   primary: true },
            { key: 'link2',    label: 'Recibe asesoría',    icon: 'bi-headset' },
            { key: 'maps',     label: 'Ubicación',          icon: 'bi-geo-alt-fill' },
        ],
    },
    inmobiliarias: {
        cta: 'Solicita tu asesoría',
        tiles: [
            { key: 'about',    label: 'Sobre nosotros',         icon: 'bi-people-fill',   primary: true },
            { key: 'link1',    label: 'Inmuebles',              icon: 'bi-house-fill' },
            { key: 'qr',       label: 'Código QR',              icon: 'bi-qr-code' },
            { key: 'services', label: 'Nuestros servicios',     icon: 'bi-journal-text',  primary: true },
            { key: 'link2',    label: 'Agenda tu visita',       icon: 'bi-calendar-check' },
            { key: 'maps',     label: 'Ubicación',              icon: 'bi-geo-alt-fill' },
        ],
    },
    profesionales: {
        cta: 'Solicita tu asesoría',
        tiles: [
            { key: 'about',    label: 'Perfil profesional', icon: 'bi-person-badge',  primary: true },
            { key: 'link1',    label: 'Portafolio virtual', icon: 'bi-briefcase-fill' },
            { key: 'qr',       label: 'Código QR',          icon: 'bi-qr-code' },
            { key: 'services', label: 'Servicios destacados', icon: 'bi-star-fill',   primary: true },
            { key: 'link2',    label: 'Agenda tu cita',     icon: 'bi-calendar-check' },
            { key: 'maps',     label: 'Ubicación',          icon: 'bi-geo-alt-fill' },
        ],
    },
    comerciantes: {
        cta: 'Es un placer atenderte',
        tiles: [
            { key: 'about',    label: 'Sobre nosotros',     icon: 'bi-people-fill',   primary: true },
            { key: 'link1',    label: 'Catálogo virtual',   icon: 'bi-grid-fill' },
            { key: 'qr',       label: 'Código QR',          icon: 'bi-qr-code' },
            { key: 'services', label: 'Nuestros servicios', icon: 'bi-journal-text',  primary: true },
            { key: 'link2',    label: 'Recibe asesoría',    icon: 'bi-headset' },
            { key: 'maps',     label: 'Ubicación',          icon: 'bi-geo-alt-fill' },
        ],
    },
    alimentos: {
        cta: 'Solicita tu servicio',
        tiles: [
            { key: 'about',    label: 'Sobre nosotros',     icon: 'bi-people-fill',   primary: true },
            { key: 'link1',    label: 'Catálogo virtual',   icon: 'bi-grid-fill' },
            { key: 'qr',       label: 'Código QR',          icon: 'bi-qr-code' },
            { key: 'services', label: 'Nuestros servicios', icon: 'bi-journal-text',  primary: true },
            { key: 'link2',    label: 'Solicita tu domicilio', icon: 'fas fa-motorcycle' },
            { key: 'maps',     label: 'Ubicación',          icon: 'bi-geo-alt-fill' },
        ],
    },
    hospedajes: {
        cta: 'Solicita tu reserva',
        tiles: [
            { key: 'about',    label: 'Sobre nosotros',     icon: 'bi-people-fill',   primary: true },
            { key: 'link1',    label: 'Catálogo virtual',   icon: 'bi-grid-fill' },
            { key: 'qr',       label: 'Código QR',          icon: 'bi-qr-code' },
            { key: 'services', label: 'Nuestros servicios', icon: 'bi-journal-text',  primary: true },
            { key: 'link2',    label: 'Agenda tu reserva',  icon: 'bi-calendar-check' },
            { key: 'maps',     label: 'Ubicación',          icon: 'bi-geo-alt-fill' },
        ],
    },
    servicios: {
        cta: 'Solicita tu servicio',
        tiles: [
            { key: 'about',    label: 'Sobre nosotros',     icon: 'bi-people-fill',   primary: true },
            { key: 'link1',    label: 'Catálogo virtual',   icon: 'bi-grid-fill' },
            { key: 'qr',       label: 'Código QR',          icon: 'bi-qr-code' },
            { key: 'services', label: 'Nuestros servicios', icon: 'bi-journal-text',  primary: true },
            { key: 'link2',    label: 'Agenda tu cita',     icon: 'bi-calendar-check' },
            { key: 'maps',     label: 'Ubicación',          icon: 'bi-geo-alt-fill' },
        ],
    },
}

export default {
    name: 'TemplateImpulso',

    props: {
        customization: { type: Object, default: () => ({}) },
        company: { type: Object, default: () => ({}) },
        card: { type: Object, default: () => ({}) },
        services: { type: Array, default: () => [] },
        products: { type: Array, default: () => [] },
    },

    data() {
        return {
            activeModal: null,
        }
    },

    computed: {
        rubro() {
            const tipo = this.customization?.rubro?.tipo || 'empresas'
            return RUBROS[tipo] || RUBROS.empresas
        },

        aboutTile() {
            return this.rubro.tiles.find(t => t.key === 'about') || { label: 'Sobre nosotros' }
        },

        servicesTile() {
            return this.rubro.tiles.find(t => t.key === 'services') || { label: 'Nuestros servicios' }
        },

        waText() {
            return this.customization?.whatsapp?.texto || this.rubro.cta
        },

        fullName() {
            if (this.card?.full_name) return this.card.full_name
            return [this.card?.first_name, this.card?.last_name].filter(Boolean).join(' ')
        },

        // Nombre en negrita, apellido en light. Si solo llega full_name, se parte
        // en la primera palabra (nombre) y el resto (apellidos).
        firstNamePart() {
            if (this.card?.first_name) return this.card.first_name
            return (this.fullName || '').split(' ')[0] || ''
        },

        lastNamePart() {
            if (this.card?.first_name) return this.card?.last_name || ''
            return (this.fullName || '').split(' ').slice(1).join(' ')
        },

        hasLogo() {
            return !!this.company?.logo_path
        },

        // El bloque de asesor (foto + nombre + cargo) se muestra cuando la tarjeta tiene foto.
        // Sin foto, la cabecera es "solo empresa" (logo únicamente), como en la referencia.
        hasAsesor() {
            return !!this.card?.photo_path
        },

        headerLayout() {
            if (this.hasLogo && this.hasAsesor) {
                return this.customization?.cabecera?.logoHorizontal ? 'horizontal-asesor' : 'logo-asesor'
            }
            if (this.hasLogo) return 'solo-empresa'
            if (this.hasAsesor) return 'solo-asesor'
            return 'solo-empresa'
        },

        cityCountry() {
            return [this.company?.city, this.company?.country].filter(Boolean).join(' - ')
        },

        showVideo() {
            return this.customization?.video?.mostrar !== false && !!this.videoId
        },

        // Acepta la URL tal como la copia el usuario (watch, youtu.be, shorts, embed)
        // o el ID pelado. Ver @/utils/youtube.js
        videoId() {
            return extractYouTubeId(this.customization?.video?.urlId)
        },

        whatsappLink() {
            if (!this.card?.whatsapp) return '#'
            const phone = this.card.whatsapp.replace(/\D/g, '')
            const msg = this.card?.whatsapp_message
                ? `&text=${encodeURIComponent(this.card.whatsapp_message)}`
                : ''
            return `https://api.whatsapp.com/send?phone=${phone}${msg}`
        },

        qrCodeUrl() {
            const slug = this.company?.slug || 'mi-empresa'
            const cardSlug = this.card?.slug || ''
            const baseUrl = window.location.origin
            const url = cardSlug ? `${baseUrl}/${slug}/${cardSlug}` : `${baseUrl}/${slug}`
            return `https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=${encodeURIComponent(url)}`
        },

        containerStyle() {
            return {
                backgroundColor: this.customization?.general?.colorFondo || '#0a1f44',
                fontFamily: "'Poppins', sans-serif",
                minHeight: '100vh',
                display: 'flex',
                flexDirection: 'column',
            }
        },

        /* ---------- Efecto cristal (glassmorphism) ---------- */
        // Activo por defecto: si el cliente nunca tocó la opción, se asume true.
        glassEnabled() {
            return this.customization?.glass?.activar ?? true
        },

        // Opacidad, desenfoque, borde y luces son fijos: definen la identidad visual de
        // Impulso y no se exponen en el editor. El cliente solo activa/desactiva el efecto
        // y elige los colores de luz. Se ignora cualquier valor viejo en customization.
        glassOpacity() {
            return 0.15
        },

        glassBlur() {
            return 12
        },

        glassBorder() {
            return true
        },

        glassLights() {
            return true
        },

        // Lámina de cristal que cubre toda la tarjeta.
        glassPanelStyle() {
            if (!this.glassEnabled) return {}
            return {
                background: `rgba(255, 255, 255, ${this.glassOpacity})`,
                backdropFilter: `blur(${this.glassBlur}px)`,
                WebkitBackdropFilter: `blur(${this.glassBlur}px)`,
                border: this.glassBorder
                    ? '1px solid rgba(255, 255, 255, 0.18)'
                    : '1px solid transparent',
            }
        },

        ambient1Style() {
            return { background: this.customization?.glass?.colorLuz1 || '#7c3aed' }
        },

        ambient2Style() {
            return { background: this.customization?.glass?.colorLuz2 || '#2f6fed' }
        },

        // Tercera luz: mezcla de las dos, para que el centro no quede muerto.
        ambient3Style() {
            return { background: this.customization?.glass?.colorLuz1 || '#2f6fed' }
        },

        // Superficies internas: van DENTRO de la lámina, así que se oscurecen en vez de
        // aclararse (si no, se funden con el panel). Sin blur propio: ya lo aporta el panel.
        glassSurface() {
            if (!this.glassEnabled) return {}
            return {
                background: 'rgba(0, 0, 0, 0.25)',
                border: this.glassBorder
                    ? '1px solid rgba(255, 255, 255, 0.12)'
                    : '1px solid transparent',
            }
        },

        /* ===== Compartir la tarjeta ===== */

        // Se arma con los slugs y no con la ruta actual: la plantilla tambien se renderiza
        // dentro del editor, donde window.location apunta al panel y no a la tarjeta.
        shareUrl() {
            const companySlug = this.company?.slug
            const cardSlug = this.card?.slug

            if (!companySlug || !cardSlug) return ''

            return `${window.location.origin}/${companySlug}/${cardSlug}`
        },

        shareLabel() {
            return 'Compartir esta tarjeta por WhatsApp'
        },

        // Sin el parametro phone, WhatsApp abre el selector de contactos: quien ve la
        // tarjeta elige a quien enviarsela. Se manda el enlace solo, sin texto que lo
        // preceda, para que WhatsApp lo muestre como vista previa del enlace.
        shareWhatsappLink() {
            if (!this.shareUrl) return '#'

            return `https://api.whatsapp.com/send?text=${encodeURIComponent(this.shareUrl)}`
        },

        // Los círculos de redes conservan fondo claro para no perder el icono negro.
        glassSocialSurface() {
            if (!this.glassEnabled) return {}
            return {
                background: 'rgba(255, 255, 255, 0.45)',
                border: this.glassBorder
                    ? '1px solid rgba(255, 255, 255, 0.35)'
                    : '1px solid transparent',
            }
        },
    },

    methods: {
        // Los presets usan Bootstrap Icons, pero algunos tiles declaran su propia familia
        // (p. ej. 'fas fa-motorcycle': Bootstrap Icons no tiene ninguna moto).
        tileIconClass(tile) {
            return tile.icon.startsWith('bi-') ? ['bi', tile.icon] : tile.icon
        },

        handleTile(tile) {
            switch (tile.key) {
                case 'about':
                case 'services':
                case 'qr':
                    this.activeModal = tile.key
                    break
                case 'maps':
                    if (this.company?.address) {
                        window.open(`https://maps.google.com/?q=${encodeURIComponent(this.company.address)}`, '_blank', 'noopener')
                    }
                    break
                case 'link1':
                    this.openExternal(this.customization?.enlaces?.enlace1)
                    break
                case 'link2':
                    this.openExternal(this.customization?.enlaces?.enlace2)
                    break
            }
        },

        openExternal(url) {
            if (url) window.open(url, '_blank', 'noopener')
        },
    },
}
</script>

<style scoped>
/* ================= CONTENEDOR ================= */
.card-template.impulso {
    position: relative;
    box-sizing: border-box;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    color: #ffffff;
}

.card-template.impulso {
    position: relative;
    overflow: hidden;
}

.card-template.impulso * {
    box-sizing: border-box;
}

/* ================= EFECTO CRISTAL ================= */
/* Luces de ambiente: manchas de color desenfocadas detrás de la lámina. */
.imp-ambient {
    position: absolute;
    border-radius: 50%;
    filter: blur(70px);
    opacity: 0.55;
    z-index: 0;
    pointer-events: none;
}

.imp-ambient-1 {
    top: -12%;
    left: -25%;
    width: 320px;
    height: 320px;
}

.imp-ambient-2 {
    bottom: -10%;
    right: -25%;
    width: 300px;
    height: 300px;
}

.imp-ambient-3 {
    top: 38%;
    right: -30%;
    width: 240px;
    height: 240px;
    opacity: 0.35;
}

/* La lámina cubre la tarjeta entera y mantiene el footer abajo. */
.imp-panel {
    position: relative;
    z-index: 1;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.imp-panel-glass {
    margin: 0.75rem;
    border-radius: 18px;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.45);
}

/* ================= CABECERA ================= */
.imp-header {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    padding: 1.75rem 1.25rem 0.5rem 1.25rem;
}

/* El logo abarca todo el espacio sin recortarse (cuadrado/redondo/horizontal/vertical) */
.imp-logo-wrap {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
}

.imp-logo {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: contain;
}

.imp-logo-lg {
    min-height: 190px;
    max-height: 240px;
}
.imp-logo-lg .imp-logo { max-height: 240px; }

.imp-logo-md {
    max-width: 62%;
    max-height: 150px;
}
.imp-logo-md .imp-logo { max-height: 150px; }

.imp-logo-banner {
    max-height: 90px;
    margin-bottom: 0.75rem;
}
.imp-logo-banner .imp-logo { max-height: 90px; }

.imp-company-name {
    font-size: 1.6rem;
    font-weight: 700;
    margin: 0;
    color: #ffffff;
}

/* Fila logo (izq) + foto asesor (der, superpuesta) */
.imp-logo-photo-row {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    width: 100%;
}

/* En esta fila el wrapper debe ceñirse al logo real. Con el width:100% heredado
   reserva un ancho fijo y un logo cuadrado deja aire a los lados por el
   object-fit: contain, lo que corre el conjunto logo+foto hacia la derecha. */
.imp-logo-photo-row .imp-logo-wrap {
    width: auto;
    flex: 0 1 auto;
    min-width: 0;
}

.imp-logo-photo-row .imp-logo {
    width: auto;
    height: auto;
    max-width: 100%;
    max-height: 150px;
}

.imp-advisor-photo {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid rgba(255, 255, 255, 0.85);
    flex-shrink: 0;
}

.imp-advisor-photo-center {
    margin: 0 auto;
}

/* Nombre: Poppins, blanco, 24px — nombre en negrita, apellido en light */
.imp-advisor-name {
    font-size: 24px;
    color: #ffffff;
    margin: 0.75rem 0 0.15rem 0;
    line-height: 1.15;
}

.imp-name-first {
    font-weight: 700;
}

.imp-name-last {
    font-weight: 300;
}

/* Cargo: Poppins, blanco sin negrita, 14px, MAYÚSCULAS */
.imp-advisor-role {
    font-size: 14px;
    font-weight: 400;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: #ffffff;
    margin: 0;
}

/* ================= CONTENIDO ================= */
.imp-content {
    padding: 1rem 1.25rem 0 1.25rem;
    display: flex;
    flex-direction: column;
    gap: 1rem;
    /* La tarjeta se embebe en anchos distintos (375px en el preview del editor,
       ~405px en la ruta pública), y ese ancho no es el de la ventana: por eso los
       ajustes de abajo van por container query y no por media query. */
    container-type: inline-size;
    container-name: impcontent;
}

/* ================= CTA WHATSAPP (fijo) ================= */
.imp-wa-btn {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    background: #22c35e;              /* verde WhatsApp, sin transparencia */
    border-radius: 14px;             /* esquinas redondeadas (~20% del alto) */
    padding: 0.55rem 0.6rem;
    text-decoration: none;
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.25);
    min-height: 58px;
}

.imp-wa-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.18);
    color: #ffffff;
    font-size: 1.5rem;
    flex-shrink: 0;
}

/* Texto: Open Sans, blanco en negrita, 17px (no editable) */
.imp-wa-text {
    flex: 1;
    text-align: center;
    color: #ffffff;
    font-weight: 700;
    font-size: 17px;
}

.imp-wa-arrow {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 34px;
    height: 34px;
    border-radius: 50%;
    background: #111111;
    color: #ffffff;
    font-size: 1rem;
    flex-shrink: 0;
}

/* ================= CONTACTO RÁPIDO (fijo) ================= */
.imp-contact-row {
    display: flex;
    gap: 0.6rem;
}

.imp-contact-btn {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.4rem;
    padding: 0.6rem 0.4rem;
    border-radius: 8px;              /* ~10% */
    color: #ffffff;
    text-decoration: none;
    font-size: 0.85rem;
    font-weight: 600;
}

.imp-contact-btn i { font-size: 1rem; }
.imp-c-call { background: #2f6fed; }   /* azul */
.imp-c-mail { background: #d32f2f; }   /* rojo */
.imp-c-web  { background: #2f6fed; }   /* azul */

/* ================= VIDEO ================= */
.imp-video-wrapper {
    position: relative;
    width: 100%;
    padding-bottom: 56.25%;
    border-radius: 8px;
    overflow: hidden;
    background: #000;
}

.imp-video-wrapper iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}

/* ================= GRILLA 6 TILES (fijo) ================= */
.imp-grid {
    display: grid;
    /* minmax(0, 1fr) y no 1fr: el mínimo "auto" de 1fr es el ancho de contenido
       mínimo del tile, así que en anchos chicos (p. ej. el preview de 375px) las
       pistas se ensanchan y la grilla desborda el contenedor. */
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 0.55rem;
}

.imp-tile {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    background: rgba(0, 0, 0, 0.5);  /* negro, transparencia 50% */
    border: none;
    border-radius: 10px;             /* ~14% */
    padding: 0.55rem 0.5rem;
    cursor: pointer;
    text-align: left;
    min-height: 52px;
    min-width: 0;                    /* permite que el tile baje de su min-content */
}

.imp-tile-icon {
    color: #d4af37;                  /* dorado, no editable */
    font-size: 1.35rem;
    flex-shrink: 0;
}

.imp-tile-label {
    font-size: 12px;
    line-height: 1.1;
    color: #c9ced8;                  /* gris claro */
    min-width: 0;
    overflow-wrap: break-word;       /* red de seguridad en anchos muy chicos */
}

/* "Sobre nosotros" / "Nuestros servicios": blanco y con algo más de peso */
.imp-tile-primary .imp-tile-label {
    color: #ffffff;
    font-weight: 600;
}

/* Contenedor angosto: se aprieta el tile para que palabras como "Ubicación" o
   "Portafolio" entren enteras en vez de partirse a la mitad. */
@container impcontent (max-width: 340px) {
    .imp-tile {
        padding: 0.5rem 0.35rem;
        gap: 0.3rem;
    }

    .imp-tile-icon {
        font-size: 1.15rem;
    }

    .imp-tile-label {
        font-size: 11px;
    }
}

/* ================= REDES SOCIALES (fijo) ================= */
.imp-social {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 0.8rem;
    margin-top: 0.5rem;
}

.imp-social-circle {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.35); /* fondo blanco, transparencia 35% */
    color: #000000;                        /* icono negro */
    text-decoration: none;
    font-size: 1.2rem;
}

/* ================= PIE ================= */
.imp-footer {
    margin-top: auto;
    padding: 1.25rem 1.25rem 1.75rem 1.25rem;
    text-align: center;
}

.imp-address {
    font-size: 13px;
    color: #c9ced8;                  /* gris claro */
    font-weight: 600;
    margin: 0 0 0.15rem 0;
}

.imp-city {
    font-size: 14px;
    color: #c9ced8;
    font-weight: 600;
    margin: 0;
}

/* Hover sutil */
.imp-tile:hover,
.imp-contact-btn:hover,
.imp-wa-btn:hover,
.imp-social-circle:hover {
    filter: brightness(1.08);
    transition: all 0.2s ease;
}

/* ================= MODALES (prefijo dm-) ================= */
/* Los modales van con Teleport a body: no heredan la fuente del contenedor */
.dm-overlay {
    font-family: 'Poppins', sans-serif;
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.6);
    backdrop-filter: blur(4px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 99999;
    padding: 3rem 1.5rem;
}

.dm-box {
    background: white;
    border-radius: 16px;
    width: min(480px, 95vw);
    max-height: calc(100vh - 6rem);
    overflow-y: auto;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
}

.dm-box::-webkit-scrollbar { width: 6px; }
.dm-box::-webkit-scrollbar-track { background: transparent; }
.dm-box::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
.dm-box::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

.dm-box-sm { width: min(360px, 90vw); }

.dm-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid #e2e8f0;
}

.dm-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #1e293b;
    margin: 0;
}

.dm-close {
    width: 32px;
    height: 32px;
    background: #f1f5f9;
    border: none;
    border-radius: 8px;
    color: #64748b;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}

.dm-close:hover { background: #f5f3ff; color: #7c3aed; }

.dm-body { padding: 1.5rem; }

.dm-about-logo { text-align: center; margin-bottom: 1rem; }
.dm-about-logo img { max-width: 120px; height: auto; border-radius: 12px; }

.dm-about-name {
    font-size: 1.15rem;
    font-weight: 700;
    color: #1e293b;
    text-align: center;
    margin: 0 0 0.75rem 0;
}

.dm-about-desc {
    font-size: 0.9rem;
    color: #475569;
    line-height: 1.6;
    margin: 0 0 1.25rem 0;
    text-align: center;
}

.dm-about-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.65rem 0;
    border-top: 1px solid #f1f5f9;
    font-size: 0.9rem;
    color: #334155;
}

.dm-about-item i { color: #8b5cf6; font-size: 1rem; width: 20px; text-align: center; flex-shrink: 0; }
.dm-about-item a { color: #7c3aed; text-decoration: none; word-break: break-all; }
.dm-about-item a:hover { text-decoration: underline; }

.dm-service-item {
    display: flex;
    gap: 1rem;
    padding: 1rem 0;
    border-bottom: 1px solid #f1f5f9;
}
.dm-service-item:first-child { padding-top: 0; }
.dm-service-item:last-child { border-bottom: none; padding-bottom: 0; }

.dm-service-img { width: 64px; height: 64px; border-radius: 10px; object-fit: cover; flex-shrink: 0; }
.dm-service-info { min-width: 0; }
.dm-service-name { font-size: 0.95rem; font-weight: 600; color: #1e293b; margin: 0 0 0.25rem 0; }
.dm-service-desc { font-size: 0.85rem; color: #64748b; line-height: 1.5; margin: 0; }

.dm-empty { text-align: center; padding: 2rem 0; color: #94a3b8; }
.dm-empty i { font-size: 2.5rem; display: block; margin-bottom: 0.75rem; }
.dm-empty p { font-size: 0.9rem; margin: 0; }

.dm-qr-body { display: flex; flex-direction: column; align-items: center; padding: 2rem 1.5rem; }
.dm-qr-img { width: 200px; height: 200px; border-radius: 12px; border: 1px solid #e2e8f0; }
.dm-qr-hint { font-size: 0.85rem; color: #64748b; margin: 1rem 0 0 0; text-align: center; }
</style>

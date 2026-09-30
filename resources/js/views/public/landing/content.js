/**
 * Contenido de la landing pública (/inicio). El texto vive aquí, separado del diseño,
 * para que un cambio de copy no obligue a tocar la vista.
 */

// Valores de la página original de Canva. Se usan mientras carga /api/landing o si falla,
// para que la página nunca se vea sin precio ni contacto.
export const FALLBACK = {
    planName:  'Presencia Digital',
    regular:   69000,
    price:     39000,
    discount:  43,
    trialDays: 7,
    whatsapp:  '573043903089',
    email:     'soporte@nexoscard.com',
    guideUrl:  'https://drive.google.com/file/d/1_ovXfdCCvApJNj_JeuM_5Klj55jOn0Ru/preview',
};

export const DEMO_URL = '/comercial/asistente-virtual';

export const CLIENTS_COUNT = 25;

export const hero = {
    title: 'Tu presencia profesional y comercial en un solo enlace',
    bullets: [
        'Acceso directo e instantáneo sin descargar aplicaciones ni archivos.',
        'Enlaces directos a WhatsApp, catálogo y redes.',
        'Actualiza tus datos en tiempo real sin reimprimir.',
    ],
};

export const features = [
    { icon: 'fas fa-qrcode',           title: 'Código QR',           text: 'Compártela fácilmente en segundos' },
    { icon: 'fab fa-whatsapp',         title: 'WhatsApp Directo',    text: 'Contacto inmediato con tus clientes' },
    { icon: 'fas fa-shopping-bag',     title: 'Productos',           text: 'Muestra todo tu catálogo' },
    { icon: 'fas fa-briefcase',        title: 'Servicios',           text: 'Presenta todos tus servicios' },
    { icon: 'fas fa-hashtag',          title: 'Redes Sociales',      text: 'Todas tus redes en un solo lugar' },
    { icon: 'fas fa-map-marker-alt',   title: 'Google Maps',         text: 'Lleva a tus clientes hasta tu negocio' },
    { icon: 'far fa-play-circle',      title: 'Video Corporativo',   text: 'Enlaza tu video y presenta tu empresa de forma profesional' },
    { icon: 'fas fa-cloud-upload-alt', title: 'Hosting Incluido',    text: 'Tu tarjeta siempre disponible en línea' },
    { icon: 'fas fa-sync-alt',         title: 'Actualizaciones',     text: 'Actualiza tu información cuando lo necesites' },
    { icon: 'fas fa-mobile-alt',       title: '100% Responsive',     text: 'Se ve perfecta en cualquier pantalla' },
    { icon: 'fas fa-share-alt',        title: 'Comparte Fácilmente', text: 'Comparte por WhatsApp, QR, correo o redes' },
    { icon: 'far fa-id-card',          title: 'Información',         text: 'Teléfonos, correos y más en un solo lugar' },
];

export const steps = [
    { icon: 'far fa-user',       title: 'Crea tu cuenta', text: 'Regístrate gratis y configura tu perfil en pocos pasos' },
    { icon: 'fas fa-pencil-alt', title: 'Personaliza',    text: 'Elige una plantilla y agrega tu información, productos, servicios, redes y demás' },
    { icon: 'fas fa-share-alt',  title: 'Comparte',       text: 'Obtén tu enlace o código QR y compártelo por WhatsApp, correo o redes sociales' },
];

export const testimonials = [
    {
        quote: 'Nexos Card cambió la forma en que me conecto con mis clientes. Ahora comparto toda mi información en segundos.',
        name: 'Miladys Silva', company: 'PRASEG S.A.S', role: 'Administradora',
        place: 'Medellín, Colombia', logo: 'client-praseg',
    },
    {
        quote: 'Muy fácil de usar y súper completa. Puedo actualizar mi información cuando quiera y mis clientes siempre tienen todo a la mano.',
        name: 'Sara Montoya', company: 'MALUE SHOP STORE', role: 'Administradora',
        place: 'Saltillo, Coahuila, México', logo: 'client-malue',
    },
    {
        quote: 'Mis clientes pueden escribirme por WhatsApp y ver todos mis servicios desde mi tarjeta digital. ¡Excelente herramienta!',
        name: 'Albeiro Rivera', company: 'RIVER PETS', role: 'Depto Logístico',
        place: 'Medellín, Colombia', logo: 'client-river-pet',
    },
];

export const planPerks = [
    { icon: 'fas fa-shield-alt',  text: 'Sin cláusulas de permanencia' },
    { icon: 'fas fa-sync-alt',    text: 'Actualizaciones incluidas' },
    { icon: 'fas fa-pencil-alt',  text: 'Tu información siempre editable' },
];

export const planItems = [
    { icon: 'fas fa-server',         text: 'Hosting incluido' },
    { icon: 'fas fa-qrcode',         text: 'Código QR personalizado' },
    { icon: 'fab fa-whatsapp',       text: 'WhatsApp directo' },
    { icon: 'fas fa-share-alt',      text: 'Redes sociales incluidas' },
    { icon: 'fas fa-shopping-bag',   text: 'Productos o servicios' },
    { icon: 'fas fa-play',           text: 'Video de presentación' },
    { icon: 'fas fa-map-marker-alt', text: 'Ubicación en Google Maps' },
    { icon: 'fas fa-link',           text: 'Enlace personalizado' },
    { icon: 'fas fa-sync-alt',       text: 'Cambios y actualizaciones' },
];

export const socials = [
    { label: 'Instagram', icon: 'fab fa-instagram',  href: 'https://www.instagram.com/nexos.card/' },
    { label: 'Facebook',  icon: 'fab fa-facebook-f', href: 'https://www.facebook.com/nexoscard' },
    { label: 'YouTube',   icon: 'fab fa-youtube',    href: 'https://www.youtube.com/@NexosCard' },
];

export function money(value) {
    return new Intl.NumberFormat('es-CO', { maximumFractionDigits: 0 }).format(value);
}

export function landingImage(name) {
    return `/images/landing/${name}.webp`;
}

// El visor de Drive (/preview o /view) no descarga; el botón "Descarga la Guía" sí, así
// que se arma el enlace de descarga a partir del id del archivo.
export function guideDownloadUrl(guideUrl) {
    const id = guideUrl.match(/\/d\/([\w-]+)/)?.[1];
    return id ? `https://drive.google.com/uc?export=download&id=${id}` : guideUrl;
}

export function buildFaqs({ planName, price, trialDays }) {
    return [
        { q: '¿Qué es Nexos Card?', a: 'Es una tarjeta digital interactiva que reúne toda tu información profesional en un solo lugar y permite que tus clientes guarden tu contacto directamente en su agenda móvil con un solo toque.' },
        { q: '¿Necesito conocimientos técnicos?', a: 'No. Solo completas tu información y tu tarjeta queda lista para compartir.' },
        { q: '¿Puedo actualizar mi información?', a: 'Sí. Puedes modificar tu información y las imágenes sin costo adicional durante tu suscripción.' },
        { q: `¿Qué incluye el plan ${planName}?`, a: 'Hosting, código QR, WhatsApp, redes sociales, productos, servicios, video corporativo, Google Maps y mucho más.' },
        { q: `¿Cómo funciona la prueba gratis de ${trialDays} días?`, a: `Te registras sin ingresar tarjeta de crédito y disfrutas de todas las funciones completas por ${trialDays} días. Si decides continuar, solo pagas $${money(price)} COP al año. Sin compromisos.` },
        { q: '¿Mis clientes deben instalar algo?', a: 'No, acceden directamente desde cualquier navegador escaneando tu código QR o tocando tu enlace.' },
        { q: '¿Recibiré ayuda para configurarla?', a: 'Sí. Al registrarte recibirás acceso inmediato a una guía paso a paso y soporte directo para dejarla lista sin complicaciones.' },
    ];
}

export function buildContactItems({ whatsappUrl, email, guideUrl }) {
    return [
        {
            key: 'whatsapp', icon: 'fab fa-whatsapp', title: 'Atención inmediata por WhatsApp',
            text: 'Escríbenos directamente para resolver dudas sobre tu cuenta o personalización.',
            label: 'Escribir por WhatsApp', href: whatsappUrl,
        },
        {
            key: 'email', icon: 'fas fa-envelope', title: 'Escríbenos por correo',
            text: 'Envíanos tu consulta y te responderemos en menos de 24 horas.',
            label: 'Enviar un correo', href: `mailto:${email}`,
        },
        {
            key: 'demo', icon: 'fas fa-mobile-alt', title: 'Mira una demostración',
            text: 'Conoce Nexos Card en acción y descubre todo lo que puede hacer por tu negocio.',
            label: 'Conoce cómo funciona', to: DEMO_URL,
            note: 'La plantilla de cada tarjeta es diferente porque cada una está enfocada según el rubro de cada empresa o negocio.',
        },
        {
            key: 'guide', icon: 'fas fa-file-pdf', title: 'Guía rápida paso a paso',
            text: 'Descarga nuestra guía interactiva en PDF y aprende a crear tu tarjeta en minutos.',
            label: 'Descarga la Guía PDF', href: guideDownloadUrl(guideUrl),
        },
    ];
}

/**
 * Precio vigente, días de prueba, contacto y guía desde /api/landing. Devuelve null si
 * falla: la página se queda con FALLBACK.
 */
export async function fetchLandingData(api) {
    try {
        const { data } = await api.get('/landing');

        return {
            pricing: data.plan ? {
                name:     data.plan.display_name || FALLBACK.planName,
                regular:  data.plan.price_regular,
                price:    data.plan.effective_price,
                discount: data.plan.discount_percent,
                hasOffer: data.plan.is_offer_active,
            } : null,
            trialDays: data.trial_days || FALLBACK.trialDays,
            whatsapp:  data.contact?.support_whatsapp || FALLBACK.whatsapp,
            email:     data.contact?.support_email || FALLBACK.email,
            guideUrl:  data.user_guide_url || FALLBACK.guideUrl,
        };
    } catch {
        return null;
    }
}

export function defaultPricing() {
    return {
        name:     FALLBACK.planName,
        regular:  FALLBACK.regular,
        price:    FALLBACK.price,
        discount: FALLBACK.discount,
        hasOffer: true,
    };
}

// Las tipografías de la landing solo hacen falta en ella; no se cargan en el resto de la app.
export function loadFonts(id, href) {
    if (document.getElementById(id)) return;
    const link = document.createElement('link');
    link.id = id;
    link.rel = 'stylesheet';
    link.href = href;
    document.head.appendChild(link);
}

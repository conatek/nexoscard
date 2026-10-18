/**
 * Contenido de la guía de primeros pasos (/guia-usuario). Es la guía que vivía en
 * nexoscard.site/guia-usuario (Canva), traída a la app con el estilo de la página de inicio.
 * El texto vive aquí, separado del diseño, igual que en landing/content.js.
 *
 * Las capturas del panel son videos cortos en bucle grabados sobre la app real con la cuenta de
 * ejemplo (Café Aroma / Laura Gómez), con indicaciones en magenta como las de la guía original.
 * Viven en public/images/guia/ como <nombre>.mp4 + <nombre>.webp (póster).
 */

export function guideImage(name) {
    return `/images/guia/${name}.webp`;
}

export const heroChips = [
    { icon: 'fas fa-mouse-pointer', title: 'Sencillo', text: 'de crear' },
    { icon: 'fas fa-mobile-alt',    title: 'Diseñado', text: 'para conectar' },
    { icon: 'fas fa-share-alt',     title: 'Comparte', text: 'sin límites' },
];

export const missionOne = {
    intro: 'Ubícate en la sección «Mi Empresa» y da clic en «Nueva tarjeta».',
    introImage: { name: 'mision-nueva-tarjeta', alt: 'Botón «Nueva tarjeta» en la sección Mi Empresa', w: 1249, h: 251 },
    afterIntro: 'Se abrirá el panel de configuración para crear tu tarjeta. Comienza con los pasos 1 y 2.',
    steps: [
        {
            title: 'Tu identidad',
            text: 'Sube tu mejor foto, tu logo y tus datos básicos.',
        },
        {
            title: 'Tu canal de cierre',
            text: 'Enlaza tu número de teléfono, WhatsApp y correo. Da clic en «Guardar cambios» o «Crear tarjeta», según como aparezca, y regresarás a la pantalla anterior.',
            image: { name: 'mision-editar-tarjeta', alt: 'Formulario de la tarjeta con el botón para guardar resaltado', w: 1249, h: 956 },
        },
        {
            title: 'Publica y visualiza',
            text: 'Tu tarjeta queda lista para compartir al instante. Da clic en el enlace de tu tarjeta y previsualiza cómo va quedando.',
            image: { name: 'mision-previsualiza', alt: 'Enlace de la tarjeta resaltado para previsualizarla', w: 1249, h: 466 },
        },
    ],
};

export function buildWelcome(trialDays) {
    return [
        {
            key: 'platform', icon: 'fas fa-desktop', title: 'Qué es la plataforma',
            text: 'NEXOS Card es una plataforma que te permite crear tu tarjeta digital interactiva para compartir tu información de manera moderna, profesional y sin complicaciones.',
        },
        {
            key: 'goals', icon: 'fas fa-bullseye', title: 'Qué lograrás al finalizar',
            items: [
                'Tendrás tu tarjeta digital publicada y lista para compartir.',
                'Reunirás toda tu información en un solo lugar.',
                'Facilitarás que te contacten con un solo toque.',
                'Proyectarás una imagen más profesional y moderna.',
            ],
        },
        {
            key: 'needs', icon: 'fas fa-clipboard-list', title: 'Lo que necesitarás antes de comenzar',
            items: [
                'Información básica de tu negocio o actividad profesional.',
                'Logo y foto (opcional).',
                'Enlaces a tus redes sociales (si las tienes).',
                'Enlace a tu video de presentación (opcional).',
            ],
        },
        {
            key: 'time', icon: 'far fa-clock', title: 'Tiempo aproximado',
            text: 'Podrás crear tu tarjeta digital en aproximadamente',
            highlight: '15 a 25 minutos',
            note: `Y tienes ${trialDays} días de prueba gratis para dejarla a tu gusto.`,
        },
    ];
}

/**
 * Los 8 pasos, con la misma numeración de la guía original. Cada bloque `cards` se pinta como
 * tarjeta; `fields` es la lista de campos con icono que acompaña a una captura.
 */
export function buildSteps(trialDays) {
    return [
        {
            id: 'paso-1', title: 'Crea tu', highlight: 'cuenta',
            intro: 'El primer paso para comenzar es crear tu cuenta en NEXOS Card.',
            cards: [
                {
                    tone: 'violet', icon: 'fas fa-tasks', title: 'Qué debes hacer',
                    items: [
                        'Completa todos los campos del formulario.',
                        'Usa un correo electrónico válido.',
                        'Crea una contraseña segura y fácil de recordar.',
                        'Haz clic en «Crear cuenta».',
                    ],
                },
                {
                    tone: 'green', icon: 'fas fa-bullseye', title: 'Resultado esperado',
                    text: 'Tu cuenta será creada correctamente y podrás acceder a tu panel para comenzar a crear tu tarjeta digital.',
                },
                {
                    tone: 'amber', icon: 'far fa-lightbulb', title: 'Consejo',
                    text: 'Usa un correo que consultes con frecuencia: será el que utilices para iniciar sesión y recuperar tu cuenta si lo necesitas. Crea tu tarjeta desde un computador; te será más fácil y disfrutarás más la experiencia.',
                },
            ],
            action: { label: 'Crear cuenta', to: '/register', icon: 'fas fa-user-plus' },
            image: { name: 'paso-1-registro', alt: 'Formulario «Crear Cuenta»', w: 1425, h: 476 },
        },
        {
            id: 'paso-2', title: 'Conoce tu', highlight: 'panel principal',
            intro: 'Este es el lugar donde gestionarás toda tu información.',
            cards: [
                {
                    tone: 'violet', icon: 'fas fa-th-large', title: 'Panel de Control',
                    text: 'Es tu vista general, con accesos rápidos y el resumen de tu actividad.',
                },
                {
                    tone: 'fuchsia', icon: 'far fa-building', title: 'Mi Empresa',
                    text: 'Aquí gestionas la información de tu empresa, tus productos y tus servicios.',
                },
                {
                    tone: 'amber', icon: 'fas fa-star', title: 'Mi Plan',
                    text: 'Desde aquí revisas tu suscripción y los límites de tu plan.',
                },
            ],
            image: { name: 'paso-2-panel', alt: 'Panel principal de NEXOS Card', w: 1413, h: 553 },
        },
        {
            id: 'paso-3', title: 'Crea tu', highlight: 'empresa',
            intro: 'En este paso configurarás toda la información principal de tu empresa.',
            cards: [
                {
                    tone: 'violet', icon: 'fas fa-tasks', title: 'Qué debes hacer',
                    items: [
                        'Completa la información básica de tu empresa.',
                        'Agrega tus datos de contacto y los enlaces a tus redes sociales.',
                        'Empieza a agregar y crear tus servicios y productos.',
                    ],
                },
                {
                    tone: 'green', icon: 'fas fa-bullseye', title: 'Resultado esperado',
                    text: 'Tu empresa quedará registrada en la plataforma con su información, lista para crear tu tarjeta digital y compartirla.',
                },
                {
                    tone: 'rose', icon: 'fas fa-exclamation-triangle', title: 'Muy importante',
                    text: 'Ten en cuenta las indicaciones resaltadas en magenta en el video del panel: cada una te lleva al paso de esta guía donde se explica en detalle (pasos 4, 5, 6 y 7).',
                },
            ],
            image: { name: 'paso-3-empresa', alt: 'Página de la empresa con las indicaciones en magenta', w: 1020, h: 385 },
        },
        {
            id: 'paso-4', title: 'Completa la', highlight: 'información principal',
            intro: 'Completa los datos más importantes de tu tarjeta digital.',
            fields: [
                { icon: 'fas fa-user',        tone: 'violet',  title: 'Nombre',      text: 'Escribe tu nombre completo.' },
                { icon: 'fas fa-briefcase',   tone: 'fuchsia', title: 'Cargo',       text: 'Indica el cargo o la posición que ocupas.' },
                { icon: 'fas fa-pencil-alt',  tone: 'amber',   title: 'Descripción', text: 'Escribe una breve descripción sobre ti o tu actividad.' },
                { icon: 'far fa-image',       tone: 'green',   title: 'Miniatura',   text: 'Sube la imagen que se mostrará al compartir tu tarjeta.' },
                { icon: 'fas fa-camera',      tone: 'cyan',    title: 'Fotografía',  text: 'Agrega una foto de perfil para que las personas te reconozcan.' },
            ],
            image: { name: 'paso-4-tarjeta', alt: 'Formulario «Editar Tarjeta»', w: 970, h: 795 },
        },
        {
            id: 'paso-5', title: 'Información de', highlight: 'contacto',
            intro: 'Completa la información general de tu empresa y enlaza tus redes sociales para que todos puedan encontrarte fácilmente.',
            fields: [
                { icon: 'fas fa-list',        tone: 'violet',  title: 'Información general', text: 'Registra los datos generales de tu empresa.' },
                { icon: 'fas fa-share-alt',   tone: 'fuchsia', title: 'Redes sociales',      text: 'Enlaza tus perfiles para aumentar tu visibilidad.' },
                { icon: 'fas fa-link',        tone: 'amber',   title: 'Slug (URL pública)',  text: 'Escoge un nombre corto para el enlace de tu tarjeta.' },
                { icon: 'far fa-building',    tone: 'green',   title: 'Logo',                text: 'Sube el logo de tu empresa o marca personal.' },
                { icon: 'fas fa-globe',       tone: 'cyan',    title: 'Sitio web',           text: 'Agrega la URL de tu sitio web o página principal.' },
            ],
            image: { name: 'paso-5-empresa', alt: 'Formulario «Editar Empresa»', w: 1040, h: 790 },
        },
        {
            id: 'paso-6', title: 'Personaliza tu', highlight: 'tarjeta digital',
            intro: 'Elige la plantilla según el rubro de tu empresa o negocio y personaliza todos los elementos de tu tarjeta digital.',
            fields: [
                { icon: 'fas fa-store',      tone: 'green',   title: 'Elegir plantilla',      text: 'Selecciona la plantilla según el rubro de tu empresa o negocio.' },
                { icon: 'fas fa-play',       tone: 'fuchsia', title: 'Video de presentación', text: 'Destaca tu negocio agregando un video de presentación.' },
                { icon: 'fas fa-qrcode',     tone: 'amber',   title: 'Botones informativos',  text: 'Agrega y enlaza información clave para que te conozcan y te encuentren fácil.' },
            ],
            note: { icon: 'fas fa-mobile-alt', title: 'Para publicar tu tarjeta', text: 'Regresa al paso 3 y sigue las indicaciones.', to: 'paso-3' },
            image: { name: 'paso-6-plantilla', alt: 'Editor de Plantilla con la vista previa de la tarjeta', w: 1200, h: 1133 },
        },
        {
            id: 'paso-7', title: '¡Felicitaciones!', highlight: 'Tu tarjeta ya está lista',
            intro: 'Tu tarjeta digital fue creada con éxito. Ahora puedes compartirla y empezar a conectar con más personas.',
            checksTitle: 'Comprueba que todo funcione correctamente',
            checks: [
                { icon: 'fab fa-whatsapp', tone: 'whatsapp', title: 'WhatsApp', text: 'Verifica que el botón lleve directamente a WhatsApp.' },
                { icon: 'fas fa-qrcode',   tone: 'violet',   title: 'QR',       text: 'Escanea el código y verifica que abra tu tarjeta.' },
                { icon: 'fas fa-play',     tone: 'rose',     title: 'Video',    text: 'Reproduce tu video de presentación y verifica que se vea bien.' },
                { icon: 'fas fa-hashtag',  tone: 'cyan',     title: 'Redes',    text: 'Revisa que todos tus enlaces a redes funcionen correctamente.' },
            ],
            nextTitle: '¿Qué hacer ahora?',
            next: [
                'Guarda tu tarjeta digital en tu teléfono.',
                'Comparte tu tarjeta digital con tus contactos.',
                'Personaliza o actualiza tu información cuando lo necesites.',
                'Usa tus botones y enlaces para facilitar el contacto.',
                'Haz crecer tu red y tu negocio.',
            ],
            closing: 'Comparte, conecta y haz crecer tu negocio',
        },
        {
            id: 'paso-8', title: 'Mi', highlight: 'Plan',
            intro: 'Aquí puedes ver el estado de tu plan, el tiempo de prueba y el uso de recursos de tu cuenta.',
            cards: [
                {
                    tone: 'violet', icon: 'fas fa-book-open', title: '¿Qué significa?',
                    text: 'Aquí encontrarás el estado de tu suscripción, el tiempo restante de tu prueba gratuita y el uso de los recursos disponibles.',
                },
                {
                    tone: 'cyan', icon: 'fas fa-cog', title: '¿Cómo funciona?',
                    text: `Durante los ${trialDays} días de prueba puedes crear y publicar 1 tarjeta digital. También puedes usar productos y servicios según los límites mostrados.`,
                },
                {
                    tone: 'green', icon: 'far fa-calendar-check', title: '¿Cuándo mejorarlo?',
                    text: 'Al finalizar la prueba, mejora tu plan para activar tu suscripción anual y seguir disfrutando de todas las funcionalidades sin interrupciones.',
                },
            ],
            image: { name: 'paso-8-plan', alt: 'Página «Mi Suscripción»', w: 1020, h: 675 },
        },
    ];
}

export const upgrade = {
    title: 'Mejora tu plan y activa tu suscripción anual',
    text: 'Al mejorar tu plan, tu tarjeta se activa con una suscripción de 1 año con todas las funcionalidades que ya conoces, sin límites de uso.',
    teamTitle: '¿Necesitas más tarjetas para tu equipo?',
    teamText: 'Contáctanos para adquirir un paquete para equipos con precio especial.',
};

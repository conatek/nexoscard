import api from '@/services/api.js';

/**
 * Datos de "Atención y soporte" del pie de página. El WhatsApp y el correo salen de
 * app_settings (Admin > Configuración) vía /api/landing; la ciudad es fija.
 */

// Se usan mientras carga /api/landing o si falla, para que el pie nunca quede vacío.
export const SUPPORT_FALLBACK = {
    whatsapp: '573206221447',
    email:    'info@nexoscard.com',
};

export const SUPPORT_CITY = 'Medellín, Colombia';

// Una sola petición por carga de la app: la comparten la página de inicio, la guía, el
// panel de login/registro y todos los pies de página.
let landingRequest = null;

export function getLandingData() {
    if (!landingRequest) {
        landingRequest = api.get('/landing')
            .then(({ data }) => data)
            .catch((error) => {
                landingRequest = null; // que el siguiente intento vuelva a pedirla
                throw error;
            });
    }

    return landingRequest;
}

export async function getSupportContact() {
    try {
        const data = await getLandingData();

        return {
            whatsapp: data.contact?.support_whatsapp || SUPPORT_FALLBACK.whatsapp,
            email:    data.contact?.support_email || SUPPORT_FALLBACK.email,
        };
    } catch {
        return { ...SUPPORT_FALLBACK };
    }
}

/** 573206221447 → "+57 3206221447" (así lo pidió el cliente). */
export function formatWhatsapp(digits) {
    if (!digits) return '';

    return digits.startsWith('57') && digits.length === 12
        ? `+57 ${digits.slice(2)}`
        : `+${digits}`;
}

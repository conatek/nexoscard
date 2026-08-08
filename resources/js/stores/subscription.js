import { reactive } from 'vue';
import subscriptionService from '@/services/subscriptionService.js';

/**
 * Estado de la suscripción compartido por la app.
 *
 * Existe por el banner: se monta una sola vez en `PublicApp.vue`, fuera del router-view,
 * así que cargaba su estado en `created()` y nunca volvía a mirarlo. Justo después de
 * pagar seguía diciendo "tu periodo ha expirado, renueva" sobre la pantalla de "pago
 * aprobado", y solo se corregía recargando la página entera.
 *
 * Quien cambie la suscripción (hoy el checkout) llama a `refresh()`.
 */
const state = reactive({
    status: null,
    daysRemaining: null,
    loaded: false,
});

export function useSubscription() {
    async function refresh() {
        try {
            const { data } = await subscriptionService.current();

            state.status = data.subscription?.status ?? null;
            state.daysRemaining = data.days_remaining ?? null;
        } catch {
            // Un fallo de red no debe dejar la app sin banner ni romper el checkout:
            // se conserva lo último que se supo.
        } finally {
            state.loaded = true;
        }
    }

    return { state, refresh };
}

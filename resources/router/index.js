import { createRouter, createWebHistory } from 'vue-router';
import Login    from '../views/Login.vue';
import Register from '../views/Register.vue';
import Home     from '../views/Home.vue';
import NotFound from '../views/NotFound.vue';

// Empresas (admin)
import CompanyIndex  from '../js/views/company/CompanyIndex.vue';
import CompanyCreate from '../js/views/company/CompanyCreate.vue';
import CompanyShow   from '../js/views/company/CompanyShow.vue';
import CompanyEdit   from '../js/views/company/CompanyEdit.vue';

// Tarjetas (admin)
import CardCreate from '../js/views/card/CardCreate.vue';
import CardEdit   from '../js/views/card/CardEdit.vue';

// Vistas públicas
import CompanyPublic from '../js/views/public/CompanyPublic.vue';
import CardPublic    from '../js/views/public/CardPublic.vue';

const routes = [
    // --- Auth ---
    {
        path: '/login',
        name: 'login',
        component: Login,
        meta: { guest: true },
    },
    {
        path: '/register',
        name: 'register',
        component: Register,
        meta: { guest: true },
    },

    // --- Panel ---
    {
        path: '/',
        name: 'home',
        component: Home,
        meta: { requiresAuth: true },
    },

    // --- Empresas ---
    {
        path: '/empresas',
        name: 'companies.index',
        component: CompanyIndex,
        meta: { requiresAuth: true },
    },
    {
        path: '/empresas/crear',
        name: 'companies.create',
        component: CompanyCreate,
        meta: { requiresAuth: true },
    },
    {
        path: '/empresas/:id',
        name: 'companies.show',
        component: CompanyShow,
        meta: { requiresAuth: true },
    },
    {
        path: '/empresas/:id/editar',
        name: 'companies.edit',
        component: CompanyEdit,
        meta: { requiresAuth: true },
    },

    // --- Tarjetas (anidadas bajo empresa) ---
    {
        path: '/empresas/:companyId/tarjetas/crear',
        name: 'cards.create',
        component: CardCreate,
        meta: { requiresAuth: true },
    },
    {
        path: '/empresas/:companyId/tarjetas/:cardId/editar',
        name: 'cards.edit',
        component: CardEdit,
        meta: { requiresAuth: true },
    },

    // --- Vistas públicas de tarjetas (al final, antes del 404) ---
    {
        path: '/:companySlug',
        name: 'public.company',
        component: CompanyPublic,
        meta: { layout: 'public' },
    },
    {
        path: '/:companySlug/:cardSlug',
        name: 'public.card',
        component: CardPublic,
        meta: { layout: 'public' },
    },

    // --- 404 ---
    {
        path: '/:pathMatch(.*)*',
        name: 'not-found',
        component: NotFound,
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    const token = localStorage.getItem('auth_token');

    if (to.meta.requiresAuth && !token) {
        return next({ name: 'login' });
    }

    if (to.meta.guest && token) {
        return next({ name: 'home' });
    }

    next();
});

export default router;

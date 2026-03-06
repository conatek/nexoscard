import './bootstrap';

import { createApp } from 'vue';
import router from './../router';
import components from '@/components';
import { useAuth } from '@/stores/auth';

const loadApp = async () => {
    const auth = useAuth();
    await auth.loadFromStorage();

    const isAdmin = window.location.pathname.startsWith('/admin');

    const { default: App } = await (isAdmin
        ? import('./components/AdminApp.vue')
        : import('./components/PublicApp.vue'));

    const app = createApp(App);
    app.use(router);

    app.provide('auth', auth);

    Object.keys(components).forEach(key => {
        app.component(key, components[key]);
    });

    app.mount('#app');
};

loadApp();

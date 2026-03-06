// import './bootstrap';

// import {createApp} from 'vue';
// import components from '@/components';

// import { library } from '@fortawesome/fontawesome-svg-core';
// import { fas } from '@fortawesome/free-solid-svg-icons';
// import { far } from '@fortawesome/free-regular-svg-icons';
// import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

// import VueSweetalert2 from 'vue-sweetalert2';
// import 'sweetalert2/dist/sweetalert2.min.css';

// const app = createApp({});

// app.use(VueSweetalert2);

// Object.keys(components).forEach(key => {
//     app.component(key, components[key]);
// });

// library.add(fas);
// library.add(far);
// app.component('font-awesome-icon', FontAwesomeIcon);

// app.mount('#app');

// ----------------------------

import { createApp } from 'vue';
import router from './../router';
import components from '@/components';

const isAdmin = window.location.pathname.startsWith('/admin');

const loadApp = async () => {
    const { default: App } = await (isAdmin
        ? import('./components/AdminApp.vue')
        : import('./components/PublicApp.vue'));

    const app = createApp(App);
    app.use(router);

    Object.keys(components).forEach(key => {
        app.component(key, components[key]);
    });

    app.mount('#app');
};

loadApp();

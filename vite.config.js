// import { defineConfig } from 'vite';
// import laravel from 'laravel-vite-plugin';
// import vue from '@vitejs/plugin-vue';
// import path from 'path';

// export default defineConfig({
//     plugins: [
//         vue(),
//         laravel({
//             input: [
//                 'resources/sass/app.scss',
//                 'resources/css/app.css',
//                 'resources/js/app.js',
//             ],
//             refresh: true,
//         }),
//     ],
//     resolve: {
//         alias: {
//             'vue': 'vue/dist/vue.esm-bundler',
//             '@': path.resolve(__dirname, './resources/js'),
//         }
//     },
//     build: {
//         manifest: true,
//         outDir: 'public/build',
//         rollupOptions: {
//             external: [
//                 'node_modules/@fortawesome/fontawesome-svg-core',
//                 'node_modules/@fortawesome/free-solid-svg-icons',
//                 'node_modules/@fortawesome/free-regular-svg-icons',
//                 'node_modules/@fortawesome/vue-fontawesome',
//             ],
//         }
//       }
// });

// --------------------------------------------

// import { defineConfig } from 'vite';
// import vue from '@vitejs/plugin-vue';
// import path from 'path';


// export default defineConfig({
//     plugins: [vue()],
//     resolve: {
//         alias: {
//             '@': path.resolve(__dirname, 'resources/js'),
//         },
//     },
//     server: {
//         historyApiFallback: true, // Permite recargar páginas sin perder el enrutamiento
//     },
// });

// --------------------------------------------

import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import { VitePWA } from 'vite-plugin-pwa';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.js'],
            refresh: true,
        }),
        vue(),
        VitePWA({
            registerType: 'autoUpdate',
            outDir: 'public',
            scope: '/',
            base: '/',
            manifest: false,
            injectRegister: false,
            workbox: {
                globDirectory: 'public/build',
                globPatterns: ['**/*.{js,css}'],
                navigateFallback: null,
                swDest: 'public/sw.js',
                runtimeCaching: [
                    {
                        urlPattern: /\.(?:js|css|woff2?|png|jpg|jpeg|gif|svg|ico)$/,
                        handler: 'StaleWhileRevalidate',
                        options: {
                            cacheName: 'static-assets',
                            expiration: {
                                maxEntries: 100,
                                maxAgeSeconds: 30 * 24 * 60 * 60,
                            },
                        },
                    },
                    {
                        urlPattern: /^https:\/\/fonts\.googleapis\.com/,
                        handler: 'StaleWhileRevalidate',
                        options: {
                            cacheName: 'google-fonts-stylesheets',
                        },
                    },
                    {
                        urlPattern: /^https:\/\/fonts\.gstatic\.com/,
                        handler: 'CacheFirst',
                        options: {
                            cacheName: 'google-fonts-webfonts',
                            expiration: {
                                maxEntries: 30,
                                maxAgeSeconds: 365 * 24 * 60 * 60,
                            },
                        },
                    },
                    {
                        urlPattern: /^https:\/\/res\.cloudinary\.com/,
                        handler: 'CacheFirst',
                        options: {
                            cacheName: 'cloudinary-images',
                            expiration: {
                                maxEntries: 60,
                                maxAgeSeconds: 30 * 24 * 60 * 60,
                            },
                        },
                    },
                    {
                        urlPattern: /\/api\//,
                        handler: 'NetworkFirst',
                        options: {
                            cacheName: 'api-cache',
                            expiration: {
                                maxEntries: 50,
                                maxAgeSeconds: 24 * 60 * 60,
                            },
                            networkTimeoutSeconds: 10,
                        },
                    },
                ],
            },
        }),
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources/js'),
        },
    },
    server: {
        historyApiFallback: true,
    },
});

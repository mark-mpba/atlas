import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/docs/theme.css',
                'resources/css/docs/theme_extra.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],

    css: {
        transformer: 'lightningcss',
        lightningcss: {
            errorRecovery: true,
        },
    },

    build: {
        cssMinify: 'lightningcss',
    },
});

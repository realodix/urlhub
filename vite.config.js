import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

const host = 'urlhub.test';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/main.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
    server: {
        host,
        hmr: { host },
    },
});

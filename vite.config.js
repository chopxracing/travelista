import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.js'],
            refresh: true,
        }),
        vue(),
    ],
    server: {
        host: '0.0.0.0',  // важно для Docker
        port: 5173,
        hmr: {
            host: 'localhost',  // или IP вашего хоста
        },
        watch: {
            usePolling: true,  // для Docker на некоторых системах
        },
    },
});

import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/login.css',
                'resources/js/login.js',
                'resources/css/register.css',
                'resources/js/register.js',
                'resources/css/bookRegister.css',
                'resources/js/bookRegister.js',
                'resources/css/profile.css',
                'resources/js/profile.js',
                'resources/css/about.css',
                'resources/js/about.js',
                'resources/css/bookSell.css',
                'resources/js/bookSell.js',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        host: '127.0.0.1', // Força o uso de IPv4
        port: 3000,        // Porta alternativa
    },
});

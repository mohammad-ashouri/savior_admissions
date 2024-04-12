import {defineConfig} from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import tailwindcss from 'tailwindcss';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/login.js',
                'resources/js/signup.js',
                'resources/Swals/SMSSent.js',
                'resources/Swals/EmailSent.js',
                'resources/Swals/SMSSendingFailed.js',
                'resources/Swals/EmailSendingFailed.js',
            ],
            refresh: true,
        }),
        vue(),
    ],
    css: {
        postcss: {
            plugins: [
                tailwindcss('./tailwind.config.js'),
            ],
        },
    },
});

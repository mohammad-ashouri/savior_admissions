import {defineConfig} from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import tailwindcss from 'tailwindcss';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/sass/app.scss',
                'resources/js/app.js',
                'resources/js/login.js',
                'resources/js/signup.js',
                'resources/js/Swals/SMSSent.js',
                'resources/js/Swals/EmailSent.js',
                'resources/js/Swals/SMSSendingFailed.js',
                'resources/js/Swals/EmailSendingFailed.js',
                'resources/js/Swals/WrongToken.js',
                'resources/js/Swals/MobileInvalid.js',
                'resources/js/Swals/MobileError.js',
                'resources/js/Swals/EmailInvalid.js',
                'resources/js/Swals/EmailError.js',
                'resources/js/Swals/PhoneCodeInvalid.js',
                'resources/js/Swals/MobileExists.js',
                'resources/js/Swals/CaptchaError.js',
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

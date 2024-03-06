/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./node_modules/flowbite/**/*.js"
    ],

    darkMode: 'class',

    theme: {
        extend: {
            colors: {
                'light-theme-color-nav-base': '#D2E69C',
                'light-theme-color-base': '#F3F2C9',
            },
        },
    },
    plugins: [
        require('flowbite/plugin')
    ],
}


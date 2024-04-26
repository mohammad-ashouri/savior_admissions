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
                'light-theme-color-nav-base': '#409ca3',
                'light-theme-color-base': '#9ddae0',
            },
        },
    },
    plugins: [
        require('flowbite/plugin')({
            charts: true,
        })
    ],
}


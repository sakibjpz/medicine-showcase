import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                med: {
                    50: '#f0fdfa',
                    100: '#ccfbf1',
                    200: '#99f6e4',
                    300: '#5eead4',
                    400: '#2dd4bf',
                    500: '#14b8a6',
                    600: '#0d9488',
                    700: '#0f766e',
                    800: '#115e59',
                    900: '#0f3a4a',
                    950: '#08292d',
                },
                navy: {
                    700: '#0f3a4a',
                    800: '#0b2b36',
                    900: '#082029',
                    950: '#041217',
                },
            },
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            boxShadow: {
                'header': '0 4px 20px rgba(11, 30, 62, 0.08)',
                'mega': '0 12px 40px rgba(11, 30, 62, 0.12)',
            },
        },
    },

    plugins: [forms],
};

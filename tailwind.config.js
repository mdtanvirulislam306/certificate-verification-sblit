import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Plus Jakarta Sans', 'Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    // SkillBuilders logo colors
                    navy: '#0A2142',
                    'navy-deep': '#061428',
                    'navy-soft': '#12305A',
                    lime: '#8DC63F',
                    'lime-dark': '#6FA32E',
                    'lime-soft': '#A4D65E',
                    'lime-muted': '#EAF6D8',
                    muted: '#F2F4F7',
                    border: '#E5E9F0',
                    // legacy aliases mapped to brand
                    green: '#8DC63F',
                    'green-dark': '#6FA32E',
                    'green-deep': '#0A2142',
                    'green-soft': '#A4D65E',
                    blue: '#0A2142',
                    sky: '#8DC63F',
                    purple: '#6FA32E',
                    orange: '#8DC63F',
                },
            },
            boxShadow: {
                card: '0 8px 24px rgba(10, 33, 66, 0.08)',
                soft: '0 4px 14px rgba(10, 33, 66, 0.05)',
            },
        },
    },

    plugins: [forms],
};

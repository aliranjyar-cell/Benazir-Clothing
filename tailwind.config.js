import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                aubergine: {
                    DEFAULT: '#24152F',
                    light: '#3A2447',
                    soft: '#4E3660',
                },
                ivory: {
                    DEFAULT: '#F7F4F0',
                    deep: '#EFE9E1',
                },
                lilac: {
                    DEFAULT: '#B69CFF',
                    soft: '#EDE6FF',
                    deep: '#8B6BEC',
                },
                mint: {
                    DEFAULT: '#A7E8D0',
                    soft: '#E4F7F0',
                    deep: '#2F8F6E',
                },
                blush: {
                    DEFAULT: '#D96C86',
                    soft: '#FBE8EC',
                    deep: '#B44863',
                },
                ink: '#292330',
                muted: '#77717D',
            },
            boxShadow: {
                card: '0 1px 2px rgba(36, 21, 47, 0.04), 0 8px 24px -12px rgba(36, 21, 47, 0.08)',
            },
        },
    },

    plugins: [forms],
};

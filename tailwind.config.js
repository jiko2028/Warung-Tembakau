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
                display: ['Playfair Display', 'serif'], // For premium headings
            },
            colors: {
                primary: {
                    50: '#FBF8F6',
                    100: '#F5EFE9',
                    200: '#EBDCD0',
                    300: '#DFC2AD',
                    400: '#D1A284',
                    500: '#C2845E', // Tanah Liat / Terracotta lembut
                    600: '#A46543',
                    700: '#864E35', // Coklat Kayu
                    800: '#6C402E',
                    900: '#583629',
                    950: '#2F1B14',
                },
                secondary: {
                    50: '#F5F7F5',
                    100: '#E9EFE9',
                    200: '#D1DDD1',
                    300: '#AFC3AF',
                    400: '#89A489',
                    500: '#688668', // Hijau Daun / Sage
                    600: '#506A50', // Hijau Hutan
                    700: '#415541',
                    800: '#354435',
                    900: '#2C382C',
                    950: '#171F17',
                },
                paper: '#FAFAF9', // Stone-50-like, warm white
                cream: '#F3EFEA', // Beige lembut
            },
            fontFamily: {
                sans: ['Outfit', 'Inter', ...defaultTheme.fontFamily.sans],
                display: ['Playfair Display', 'serif'],
            },
            borderRadius: {
                '4xl': '2rem',
            },
            boxShadow: {
                'soft': '0 4px 20px -2px rgba(0, 0, 0, 0.05)',
                'glow': '0 0 15px rgba(194, 132, 94, 0.3)',
            },
            animation: {
                'fade-in': 'fadeIn 0.5s ease-out',
                'slide-up': 'slideUp 0.5s ease-out',
                'float': 'float 3s ease-in-out infinite',
            },
            keyframes: {
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                slideUp: {
                    '0%': { transform: 'translateY(20px)', opacity: '0' },
                    '100%': { transform: 'translateY(0)', opacity: '1' },
                },
                float: {
                    '0%, 100%': { transform: 'translateY(0)' },
                    '50%': { transform: 'translateY(-10px)' },
                },
            },
        },
    },

    plugins: [forms],
};

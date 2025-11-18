import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: '#2C72B3',
                'primary-light': '#4A8FCC',
                'primary-dark': '#1E4F7F',
                secondary: '#5BA3E0',
                'secondary-light': '#7DB9E8',
                'secondary-dark': '#2E5F8F',
            },
        },
    },
    plugins: [require('daisyui')],
    daisyui: {
        themes: [
            {
                bccflow: {
                    "primary": "#2C72B3",
                    "secondary": "#5BA3E0",
                    "accent": "#4A8FCC",
                    "neutral": "#2B3440",
                    "base-100": "#ffffff",
                    "info": "#3ABFF8",
                    "success": "#36D399",
                    "warning": "#FBBD23",
                    "error": "#F87272",
                },
            },
        ],
    },
};

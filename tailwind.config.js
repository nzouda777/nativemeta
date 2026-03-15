/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.jsx',
    ],
    theme: {
        extend: {
            colors: {
                'dark': '#0A0A0B',
                'dark-lighter': '#121214',
                'gold': {
                    'light': '#2d7ff9',
                    'DEFAULT': '#5b9bff',
                    'dark': '#1a5fd0',
                },
                'primary': '#6366F1', // Indigo électrique
                'secondary': '#4F46E5',
                'accent': '#10B981', // Emeraude
            },
            fontFamily: {
                'clash': ['Clash Display', 'sans-serif'],
                'jakarta': ['Plus Jakarta Sans', 'sans-serif'],
                'inter': ['Inter', 'sans-serif'],
                'syne': ['Syne', 'sans-serif'],
            },
            animation: {
                'gradient': 'gradient 8s linear infinite',
                'float': 'float 6s ease-in-out infinite',
                'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
            },
            keyframes: {
                gradient: {
                    '0%, 100%': { 'background-position': '0% 50%' },
                    '50%': { 'background-position': '100% 50%' },
                },
                float: {
                    '0%, 100%': { transform: 'translateY(0)' },
                    '50%': { transform: 'translateY(-20px)' },
                },
            },
            backgroundImage: {
                'glass-gradient': 'linear-gradient(135deg, rgba(255, 255, 255, 0.05) 0%, rgba(255, 255, 255, 0.01) 100%)',
                'gold-gradient': 'linear-gradient(135deg, #f59e0b 0%, #B45309 100%)',
            },
            boxShadow: {
                'glass': '0 8px 32px 0 rgba(0, 0, 0, 0.37)',
                'gold-glow': '0 0 20px rgba(245, 158, 11, 0.3)',
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
    ],
};

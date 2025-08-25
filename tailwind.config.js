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
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },

            colors: {
                primary: '#F7FAFC',
                secondary: '#8E9CBA',
                tertiary: '#2D3748',
                softBlue: '#089ABE',
                midBlue: '#3182CE',
                strongBlue: '#2E3D3A',
                goodYellow: '#F5C55B',
                orangeYellow: '#F6AD55',
                approveGreen: '#38A169',
                dangerRed: '#E53E3E',
                goodPurple: '#7B5695'
            },
        },
    },

    plugins: [forms],
};

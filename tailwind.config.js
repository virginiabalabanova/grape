import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './vendor/area17/twill-navigation/views/**/*.blade.php', // Add other paths if needed
        './vendor/rawilk/laravel-base/resources/**/*.blade.php',
        './vendor/rawilk/laravel-form-components/resources/**/*.blade.php',
        './vendor/wire-elements/modal/resources/views/*.blade.php',
        './vendor/wire-elements/spotlight/resources/views/*.blade.php',
        './vendor/wireui/wireui/resources/**/*.blade.php',
        './vendor/wireui/wireui/ts/**/*.ts',
        './vendor/wireui/wireui/src/View/**/*.php'
    ],

    theme: {
        extend: {
            fontFamily: {
                // Set Poppins as the default sans-serif font using a CSS variable
                sans: ['var(--font-sans)', ...defaultTheme.fontFamily.sans],
            }
        },
    },

    plugins: [forms],
};
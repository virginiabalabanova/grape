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
        './vendor/wireui/wireui/src/View/**/*.php',
        './resources/views/**/*.blade.php',
        './resources/css/**/*.blade.css',
    ],
    safelist: [
        'border-blue-500',
        'border-gray-300',
        'ring-2',
        'ring-offset-2',
        'ring-blue-500',
        'ring-black',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['var(--font-sans)', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};

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
        './storage/app/theme-source.css',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['var(--font-sans)', ...defaultTheme.fontFamily.sans],
                primary: ['var(--font-primary)', ...defaultTheme.fontFamily.sans],
                secondary: ['var(--font-secondary)', ...defaultTheme.fontFamily.sans],
            },
            borderRadius: {
                sm: 'var(--radius-sm)',
                md: 'var(--radius-md)',
                lg: 'var(--radius-lg)',
            },
            letterSpacing: {
                tightest: 'var(--tracking-tightest)',
                tighter: 'var(--tracking-tighter)',
                tight: 'var(--tracking-tight)',
                normal: 'var(--tracking-normal)',
                wide: 'var(--tracking-wide)',
                wider: 'var(--tracking-wider)',
                widest: 'var(--tracking-widest)',
                'widest-px': 'var(--tracking-widest-px)',
            },
            spacing: {
                '20px': 'var(--spacing-20px)',
                '30px': 'var(--spacing-30px)',
            }
        },
    },

    plugins: [forms],
};

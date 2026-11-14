import forms from '@tailwindcss/forms';
import colors from 'tailwindcss/colors';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './app/Livewire/**/*Table.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
        './vendor/filament/**/*.blade.php',
    ],
    darkMode: 'class',
    theme: {
        extend: {
            colors: {
                'dark': colors.neutral,
                'pg-primary': colors.gray, // PowerGrid
            },
        },
    },
    plugins: [
        // forms({strategy: 'class'})
        forms
    ],
};

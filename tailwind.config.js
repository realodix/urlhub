import forms from '@tailwindcss/forms';
import colors from 'tailwindcss/colors';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    theme: {
        extend: {
            colors: {
                'dark': colors.neutral,
            },
        },
    },
    plugins: [
        // forms({strategy: 'class'})
        forms
    ],
};

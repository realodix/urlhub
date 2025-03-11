import forms from '@tailwindcss/forms';
import presetPowerGrid from './vendor/power-components/livewire-powergrid/tailwind.config.js';
import colors from 'tailwindcss/colors';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './app/Livewire/**/*Table.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
        './vendor/filament/**/*.blade.php',
        './vendor/power-components/livewire-powergrid/resources/views/**/*.php',
        './vendor/power-components/livewire-powergrid/src/Themes/Tailwind.php',
    ],
    presets: [
        presetPowerGrid,
    ],
    darkMode: 'class',
    theme: {
        extend: {
            colors: {
                'primary': colors.blue,
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

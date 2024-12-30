import forms from '@tailwindcss/forms';
import presetPowerGrid from './vendor/power-components/livewire-powergrid/tailwind.config.js';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./app/Livewire/**/*Table.php",
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./vendor/filament/**/*.blade.php",
        "./vendor/power-components/livewire-powergrid/resources/views/**/*.php",
        "./vendor/power-components/livewire-powergrid/src/Themes/Tailwind.php",
    ],
    presets: [
        presetPowerGrid,
    ],
    theme: {
        extend: {
            colors: {
                "uh-blue": "#3d5b99",
                "border-200": "#e7e5e4",
                "border-300": "#d6d3d1",
            },
        },
    },
    plugins: [
        // forms({strategy: 'class'})
        forms
    ],
};

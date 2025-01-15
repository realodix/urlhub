import forms from '@tailwindcss/forms';
import presetPowerGrid from './vendor/power-components/livewire-powergrid/tailwind.config.js';
import colors from 'tailwindcss/colors';

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
                "primary-50": "#eff6ff",
                "primary-100": "#dbeafe",
                "primary-200": "#bfdbfe",
                "primary-300": "#93c5fd",
                "primary-400": "#60a5fa",
                "primary-500": "#3b82f6",
                "primary-600": "#2563eb",
                "primary-700": "#1d4ed8",
                "primary-800": "#1e40af",
                "uh-blue": "#3d5b99",
                "border-200": "#e7e5e4",
                "border-300": "#d6d3d1",
                // PowerGrid
                'pg-primary': colors.gray,
            },
        },
    },
    plugins: [
        // forms({strategy: 'class'})
        forms
    ],
};

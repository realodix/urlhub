module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    './app/Http/Livewire/**/*Table.php',
    './vendor/power-components/livewire-powergrid/resources/views/**/*.php',
    './vendor/power-components/livewire-powergrid/src/Themes/Tailwind.php',
  ],
  theme: {
    extend: {
      colors: {
        'uh-blue': '#3d5b99',
        uh: {
          1: '#344767',
          '2a': 'rgb(63 152 222)',
          '2b': 'rgb(53 140 208)',
          '2c': 'rgb(43 128 194)',
        }
      }
    }
  },
  plugins: [
    require("@tailwindcss/forms")({
      strategy: 'class',
    }),
  ],
}

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
        nord: {
          pn1: '#2E3440', //
          'pn1-bold': 'rgb(41, 46, 57)',
          pn2: '#3B4252',
          pn3: '#434C5E',
          pn4: '#4C566A',
          ss1: '#D8DEE9',
          ss2: '#E5E9F0',
          ss3: '#ECEFF4',
          f1: '#8FBCBB',
          f2: '#88C0D0',
          f3: '#81A1C1',
          f4: '#5E81AC',
          a1: '#BF616A',
          a2: '#D08770',
          a3: '#EBCB8B',
          a4: '#A3BE8C',
          a5: '#B48EAD',
        },
      }
    }
  },
  plugins: [
    require("@tailwindcss/forms")({
      strategy: 'class',
    }),
  ],
}

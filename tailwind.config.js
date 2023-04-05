module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "./app/Http/Livewire/**/*Table.php",
    "./vendor/power-components/livewire-powergrid/resources/views/**/*.php",
    "./vendor/power-components/livewire-powergrid/src/Themes/Tailwind.php",
  ],
  presets: [
    require('./vendor/power-components/livewire-powergrid/tailwind.config.js'),
  ],
  theme: {
    extend: {
      colors: {
        "uh-blue": "#3d5b99",
        "bg-primary": "#f8fafc",
        uh: {
          1: "#344767",
          "2a": "rgb(63 152 222)",
          "2b": "rgb(53 140 208)",
          "2c": "rgb(43 128 194)",
          indigo: {
            50: "rgb(247 246 253)",
            100: "rgb(238 238 251)",
            200: "rgb(213 212 245)",
            300: "rgb(188 185 239)",
            400: "rgb(138 133 228)",
            500: "rgb(88 81 216)",
            600: "rgb(79 73 194)",
            700: "rgb(53 49 130)",
            800: "rgb(40 36 97)",
            900: "rgb(26 24 65)",
          },
        },
      },
    },
  },
  plugins: [
    require("@tailwindcss/forms")({
      strategy: "class",
    }),
  ],
};

let mix = require('laravel-mix');
require('laravel-mix-auto-extract');

mix.setPublicPath('public')
   .options({
      processCssUrls: false,
      autoprefixer: false
});

mix.sass('resources/sass/backend.scss', 'css')
   .js('resources/js/frontend.js', 'js/frontend.js')
   .js('resources/js/backend.js', 'js/backend.js')
   .autoExtract()
   .version();

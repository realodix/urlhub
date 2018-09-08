let mix = require('laravel-mix');
var LiveReloadPlugin = require('webpack-livereload-plugin');
require('laravel-mix-auto-extract');

mix.setPublicPath('public')
   .autoExtract()
   .options({
      autoprefixer: false,
      processCssUrls: false,
      purifyCss: true
   })
   .webpackConfig({
       plugins: [
           new LiveReloadPlugin()
       ],
   });

mix.sass('resources/sass/bootstrap-custom.scss', 'css/bootstrap-custom.css')
   .js('resources/js/frontend.js', 'js/frontend.js')
   .js('resources/js/backend.js', 'js/backend.js')
   .version([
      'public/css/backend.css',
      'public/css/frontend.css'
   ]);

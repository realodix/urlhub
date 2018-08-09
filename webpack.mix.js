let mix = require('laravel-mix');

mix.options({
 processCssUrls: false
});

mix.sass('resources/assets/sass/styles.scss', 'public/css', {outputStyle: 'expanded'})
   .sass('resources/assets/sass/bootstrap-custom/bootstrap-custom.scss', 'public/css', {outputStyle: 'expanded'})
   .js([
       'node_modules/jquery/dist/jquery.min.js',
       'node_modules/bootstrap/dist/js/bootstrap.min.js',
       //'node_modules/clipboard/dist/clipboard.min.js',
       // 'resources/assets/js/app.js',
   ], 'public/js/app.js');

mix.copyDirectory('node_modules/@fortawesome/fontawesome-free', 'public/vendor/fontawesome');

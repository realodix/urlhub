const mix = require('laravel-mix');

mix.postCss('resources/css/main.css', 'public/css', [
    require('tailwindcss/nesting'),
    require('tailwindcss'),
  ])
  .sass('resources/sass/backend.scss', 'css/backend.css')
  .sass('resources/sass/frontend.scss', 'css/frontend.css')
  .js('resources/js/frontend.js', 'js/frontend.js')
  .js('resources/js/backend.js', 'js/backend.js')
  .copyDirectory('node_modules/datatables.net-dt/images', 'public/images');

mix.extract()
  .version()
  .setPublicPath('public')
  .options({
    autoprefixer: true,
    processCssUrls: false,
  });

if (!mix.inProduction()) {
  mix.webpackConfig({
      devtool: 'source-map',
    })
    .sourceMaps()
    .browserSync({
      open: 'external',
      host: 'urlhub-next.test',
      proxy: 'urlhub-next.test'
    })
}

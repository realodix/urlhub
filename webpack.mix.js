const mix = require('laravel-mix');

mix
  .sass('resources/sass/backend/backend.scss', 'css/backend.css')
  .sass('resources/sass/frontend/frontend.scss', 'css/frontend.css')
  .js('resources/js/frontend.js', 'js/frontend.js')
  .js('resources/js/backend.js', 'js/backend.js')
  .copyDirectory('node_modules/datatables.net-dt/images', 'public/images');

mix
  .extract()
  .version()
  .setPublicPath('public')
  .options({
      autoprefixer: false,
      processCssUrls: false,
  });

if (!mix.inProduction()) {
  mix
    .webpackConfig({
        devtool: 'source-map',
    })
    .sourceMaps()
    .browserSync({
        open: 'external',
        host: 'urlhub.test',
        proxy: 'urlhub.test'
    })
}

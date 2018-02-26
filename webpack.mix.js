const { mix } = require('laravel-mix');
require('laravel-mix-purgecss');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/assets/js/hub.js', 'resources/build/js')
    .sass('resources/assets/sass/hub.scss', 'resources/build/css')
    .copy('resources/assets/icons', 'resources/build/icons')
    .copy('resources/assets/images', 'resources/build/images');
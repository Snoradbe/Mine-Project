const mix = require('laravel-mix');

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

mix
    //.sass('resources/sass/app.scss', 'public/css')
    //.sass('resources/sass/admin/app.scss', 'public/css/admin')
    //.js('resources/js/admin/app.js', 'public/js/admin')
    .styles([
		'resources/sass/client/common.css',
		'resources/sass/client/account.css',
		'resources/sass/client/account-settings.css',
		'resources/sass/client/main.css',
		'resources/sass/client/page-window.css',
		'resources/sass/client/slick.css',
	], 'public/css/app.css')
    .js('resources/js/client/store/store.js', 'public/js/client/store')
    .js('resources/js/client/store/marketplace.js', 'public/js/client/store')
    .js('resources/js/client/store/cart.js', 'public/js/client/store')

    .js('resources/js/client/status/status.js', 'public/js/client/status')

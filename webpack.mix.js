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
    .setPublicPath('public')
    .sass('resources/sass/styles.scss', 'public/css')
    .styles([
        'resources/css/datatables.css',
        'resources/css/jquery.fileuploader.css',
        'resources/css/jquery.fileuploader-theme-thumbnails.css',
    ], 'public/css/vendor.css')
    .js('resources/js/app.js', 'public/js')
    .js('resources/js/jquery.fileuploader.js', 'public/js')
    .js('resources/js/stepper.js', 'public/js')
    .js('resources/js/view-tabs.js', 'public/js')
    .js('resources/js/product-picker.js', 'public/js')
    .js('resources/js/datatable-filters.js', 'public/js')
    .js('resources/js/modules/contacts.js', 'public/js/modules')
    .js('resources/js/modules/brands.js', 'public/js/modules')
    .js('resources/js/modules/products.js', 'public/js/modules')
    .js('resources/js/modules/notifications.js', 'public/js/modules')
    .js('resources/js/modules/promo-periods.js', 'public/js/modules')
    .js('resources/js/modules/marketing-agreements.js', 'public/js/modules')
    .js('resources/js/modules/inventory-removals.js', 'public/js/modules')
    .js('resources/js/modules/pricing-adjustments.js', 'public/js/modules')
    .js('resources/js/modules/catalogue-categories.js', 'public/js/modules')
    .js('resources/js/modules/promos.js', 'public/js/modules')
    .js('resources/js/modules/promos-search.js', 'public/js/modules')
    .js('resources/js/modules/discopromos.js', 'public/js/modules')
    .js('resources/js/modules/management-signoff.js', 'public/js/modules')
    .js('resources/js/modules/finance-signoff.js', 'public/js/modules')
    .js('resources/js/uploaders/local.js', 'public/js/uploaders')
    .js('resources/js/uploaders/thumbnail.js', 'public/js/uploaders')
    .js('resources/js/uploaders/label.js', 'public/js/uploaders')
    .postCss("resources/css/tailwind.css", "public/css", [
        require("tailwindcss"),
    ])
    .sourceMaps(false, 'source-map')
    .version();

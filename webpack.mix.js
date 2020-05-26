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

mix.js('resources/js/app.js', 'public/assets/js/bundle.js')
    .sass('resources/sass/app.scss', 'public/assets/css');
mix.copy('node_modules/jqueryui/', 'public/assets/css/jqueryui');

//mix.copy('../assets/img/', 'public/assets/img/');

//mix.copy('../assets/js/', 'public/assets/js/');

//mix.copy('../assets/css/default', 'public/assets/css/default/');

mix.copy('node_modules/jquery-migrate/dist/', 'public/assets/plugins/jquery-migrate/');

mix.copy('node_modules/bootstrap-datepicker/dist/', 'public/assets/plugins/bootstrap-datepicker/');

mix.copy('node_modules/jquery-sparkline/jquery.sparkline.min.js', 'public/assets/plugins/jquery-sparkline/jquery.sparkline.min.js');

mix.copy('node_modules/autonumeric/dist/', 'public/assets/plugins/autonumeric/');

mix.copy('node_modules/moment/', 'public/assets/plugins/moment/');

mix.copy('node_modules/lity/dist/', 'public/assets/plugins/lity/');

mix.copy('node_modules/parsleyjs/', 'public/assets/plugins/parsleyjs/');

mix.copy('node_modules/select2/', 'public/assets/plugins/select2/');

//mix.copy('node_modules/sweetalert2/', 'public/assets/plugins/sweetalert2/');

mix.copy('node_modules/bootstrap-timepicker/', 'public/assets/plugins/bootstrap-timepicker/');

mix.copy('node_modules/bootstrap-combobox/', 'public/assets/plugins/bootstrap-combobox/');

mix.copy('node_modules/bootstrap-select/', 'public/assets/plugins/bootstrap-select/');

mix.copy('node_modules/bootstrap-daterangepicker/', 'public/assets/plugins/bootstrap-daterangepicker/');

mix.copy('node_modules/bootstrap-show-password/', 'public/assets/plugins/bootstrap-show-password/');

mix.copy('resources/plugins/', 'public/assets/plugins/');

mix.copy('node_modules/bootstrap3-wysihtml5-bower/dist/', 'public/assets/plugins/bootstrap3-wysihtml5/');

mix.copy('node_modules/gritter/', 'public/assets/plugins/gritter/');

mix.copy('node_modules/switchery/', 'public/assets/plugins/switchery/');

mix.copy('node_modules/select2/', 'public/assets/plugins/select2/');

mix.copy('node_modules/bootstrap3-wysihtml5-bower/dist/', 'public/assets/plugins/bootstrap3-wysihtml5/');

mix.copy('node_modules/smartwizard/', 'public/assets/plugins/smartwizard/');
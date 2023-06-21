const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .scripts([
        'node_modules/jquery/dist/jquery.js', // Carga jQuery primero
        'node_modules/datatables.net/js/jquery.dataTables.js',
        'node_modules/admin-lte/dist/js/adminlte.js',
    ], 'public/js/all.js')
    .styles([
        'node_modules/admin-lte/dist/css/adminlte.css',
        'node_modules/datatables.net-dt/css/jquery.dataTables.css',
    ], 'public/css/all.css')
    .postCss('resources/css/app.css', 'public/css', [
        require('postcss-import'),
        require('tailwindcss'),
    ])
    .webpackConfig(require('./webpack.config'));
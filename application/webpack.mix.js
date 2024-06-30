const mix = require('laravel-mix');

// Assuming your Vue entry point is index.js
mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css');

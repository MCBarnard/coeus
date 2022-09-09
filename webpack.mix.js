const mix = require('laravel-mix');

// When adding a new config, restart the watcher
mix.js('resources/js/app.js', 'public/js')
    // App Styles
    .sass('resources/scss/app.scss', 'public/css/')
    // Javascript Components
    .js('resources/js/Components/Navbar.js', 'public/js/components/')
    .js('resources/js/Components/ThreeJsBackground.js', 'public/js/components/')
    .js('resources/js/Components/StartScanButton.js', 'public/js/components/')

    // Javascript Pages
    .js('resources/js/Pages/Home.js', 'public/js/pages/')
;

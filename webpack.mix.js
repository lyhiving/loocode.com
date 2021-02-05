const mix = require('laravel-mix');

// const path = require("path");

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

//设置库的别名
mix.webpackConfig({
    resolve: {
        alias: {
            "social-share.js": "social-share.js/dist/js/social-share.min",
            "jquery": 'jquery/dist/jquery.slim',
        }
    }
});

mix.setPublicPath('dist');

//合并文件，分离库和页面JS
mix.js('resources/js/app.js', "dist/assets/js/app.js")
    .js('resources/js/posts.js', "dist/assets/js/article.js")
    .js('resources/js/user.js', "dist/assets/js/user.js")
    .styles([
        "node_modules/bootstrap/dist/css/bootstrap.css",
        "node_modules/social-share.js/dist/css/share.min.css",
        "resources/css/app.css",
    ], "public/assets/css/app.css")
    .css("resources/css/article.css", "dist/assets/css/article.css")
    .styles([
        "node_modules/filepond/dist/filepond.css",
    ], "dist/assets/css/filepond.css")
    .copyDirectory(
        ['resources/images/'],
        'dist/assets/images/'
    )
    .copyDirectory(
        [
            "node_modules/social-share.js/dist/fonts",
        ],
        'dist/assets/fonts/'
    )
    .extract(['jquery', 'popper.js', 'bootstrap', 'superagent', 'social-share.js'])
    .autoload({
        //设置自动加载全局
        jquery: ['$', 'window.jQuery', 'jQuery'],
    });

if (mix.inProduction()) {
    mix.version();
} else {
    mix.webpackConfig({
        devtool: 'inline-source-map'
    });
}

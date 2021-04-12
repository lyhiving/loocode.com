const mix = require('laravel-mix');

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
// mix.webpackConfig({
//   resolve: {
//     alias: {
//       "social-share.js": "social-share.js/dist/js/social-share.min",
//       "jquery": 'jquery/dist/jquery.slim',
//     }
//   }
// });

if (mix.inProduction()) {
  mix.setPublicPath('dist');
} else {
  mix.setPublicPath('public');
}

//合并文件，分离库和页面JS
mix.js('resources/js/app.js', "assets/js/app.js")
    .js('resources/js/posts.js', "assets/js/article.js")
    .js('resources/js/user.js', "assets/js/user.js")
    .postCss('resources/css/app.css', 'assets/css/app.css', [
      require("tailwindcss"),
    ])
    .css("resources/css/article.css", "assets/css/article.css")
    .css("node_modules/filepond/dist/filepond.css", "assets/css/filepond.css")
    .copyDirectory(
        ['resources/images/'],
        'dist/assets/images/'
    )
    .extract([])
    // .autoload({
    //     //设置自动加载全局
    //     jquery: ['$', 'window.jQuery', 'jQuery'],
    // });

if (mix.inProduction()) {
    mix.version();
} else {
    mix.webpackConfig({
        devtool: 'inline-source-map'
    });
}

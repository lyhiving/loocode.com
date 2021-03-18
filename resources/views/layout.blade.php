<!DOCTYPE html>
<html lang="">
<head>
    <meta charset="UTF-8" />
    <title>{{ $seo->title }}</title>
    <meta name="description" content="{{ $seo->description }}" />
    <meta name="keywords" content="{{ $seo->keyword }}" />
    <link href="/favicon.ico" rel="icon" sizes="32x32"/>
    <meta name="google-site-verification" content="HySQr9AQd4P4wZ8jK8glrbXDbN38fBpoLyXi50YxduU" />
    <link rel="manifest" href="/manifest.json" />
    <meta name="theme-color" content="#f4645f" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
    <link rel="stylesheet" type="text/css" href="//at.alicdn.com/t/font_338218_4wxwv706rhb.css" />
    <link rel="stylesheet" type="text/css" href="{{ mix("/assets/css/app.css")  }}"/>
    @if($user)
    <meta name="Authorization" content="{{ $user->ID }}">
    @endif
    <!-- in your header -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/devicons/devicon@v2.9.0/devicon.min.css">
    @yield("header_css")
</head>
<body>
<div class="site-wrapper">
    @include("header")
    @yield("content")
    @include("footer")
</div>
<div class="cd-top-trigger">
    <i class="iconfont icon-Top"></i>
</div>
<script type="text/javascript" src="{{ mix("/assets/js/manifest.js") }}"></script>
<script type="text/javascript" src="{{ mix("/assets/js/vendor.js") }}"></script>
<script type="text/javascript" src="{{ mix("/assets/js/app.js") }}"></script>
@yield("footer_js")
</body>
</html>


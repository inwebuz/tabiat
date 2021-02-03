<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('seo_title')</title>
    <meta name="description" content="@yield('meta_description')" />
    <meta name="keywords" content="@yield('meta_keywords')" />

    @php
		$htmlClass = [];
		$badEye = json_decode(request()->cookie('bad_eye'), true);
		if (is_array($badEye)) {
			foreach ($badEye as $key => $value) {
				if ($value != 'normal' && !in_array('bad-eye', $htmlClass)) {
					$htmlClass[] = 'bad-eye';
				}
				$htmlClass[] = 'bad-eye-' . $key . '-' . $value;
			}
		}
        $assetsVersion = env('ASSETS_VERSION', 1);
    @endphp

    <link rel="stylesheet" href="{{ asset('css/vendor.css?v=' . $assetsVersion) }}">
    <link rel="stylesheet" href="{{ asset('css/app.css?v=' . $assetsVersion) }}">

    @yield('styles')

    <link rel="apple-touch-icon" sizes="57x57" href="/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

    {!! setting('site.facebook_pixel_code') !!}

</head>
<body class="@yield('body_class')">

    <div id="app">

        <x-header />

        @yield('content')

        <x-footer />

    </div>

    <script src="{{ asset('js/app.js?v=' . $assetsVersion) }}"></script>

    @yield('scripts')

    {!! setting('site.google_analytics_code') !!}

    {!! setting('site.yandex_metrika_code') !!}

    @yield('microdata')

</body>
</html>

<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('seo_title')</title>
    <meta name="description" content="@yield('meta_description')" />
    <meta name="keywords" content="@yield('meta_keywords')" />

    <link rel="stylesheet" href="{{ asset('css/vendor.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

</head>
<body class="@yield('body_class')">

    <div id="app">

        <div class="print-page-container">
            <div class="print-page">
                @yield('content')
            </div>
        </div>

    </div>

    <script src="{{ asset('js/app.js') }}"></script>

</body>
</html>

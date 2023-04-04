<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', '')</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<section class="section">
    <div class="container">
        @include('partials.navigation')
    </div>
</section>
<section class="section">
    <div class="container">
        @yield('content', '')
    </div>
</section>

@yield('scripts', '')
</body>
</html>

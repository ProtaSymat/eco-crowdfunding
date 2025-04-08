<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>


    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/sass/fonts.scss', 'resources/js/app.js'])
    @stack('styles')
</head>
<body>
<div id="app">
    @include('partials.header')
    
    <div class="container mt-5">
        @yield('content')
    </div>
    
    @include('partials.footer')
    
    <script src="{{ asset('js/app.js') }}"></script>
    </div>
    @stack('scripts')
</body>

</html>

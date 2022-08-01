<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="device-type" content="mobile">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script type="text/javascript">
        var APP_URL = {!! json_encode(url('/')) !!};
    </script>

    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/song.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app-mobile.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-sm navbar-light navbar-laravel">
            <div class="container">
                <a class="navbar-brand {{ Request::is('songs') && Request::input('genres') != 'true' ? 'active' : '' }}" href="{{ url('/songs') }}">
                    {{ __('Songs') }}   {{ Request::segment(2) }} 
                </a>
                <a class="navbar-brand {{ Request::is('artists') ? 'active' : '' }}" href="{{ url('/artists') }}">
                     {{ __('Artists') }}
                </a>
                <a class="navbar-brand {{ Request::is('playlists') ? 'active' : '' }}" href="{{ url('/playlists') }}">
                     {{ __('Playlists') }}
                </a>
                <a class="navbar-brand {{ Request::input('genres') == 'true' ? 'active' : '' }}" href="{{ url('/songs?genres=true') }}">
                     {{ __('Genres') }}
                </a>
                <a class="navbar-brand {{ Request::is('word-cloud') ? 'active' : '' }}" href="{{ url('/word-cloud') }}">
                     {{ __('Word Cloud') }}
                </a>
            </div>
        </nav>

        <main class="py-1">
            @yield('content')
            @yield('scripts')
            @yield('styles')
        </main>
    </div>
</body>
</html>

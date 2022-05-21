<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/song.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body class="mysounds-home-page">

    <div class="mysounds-home-page-div">
        @auth
            <div><a href="{{ url('/songs') }}"><h3>Songs</h3></a></div>
            <div><a href="{{ url('/artists') }}"><h3>Artists</h3></a></div>
            <div><a href="{{ url('/playlists') }}"><h3>Playlists</h3></a></div>
            <div><a href="#" name="shuffle_songs"><h3>Shuffle Songs</h3></a></div>
            <div><a href="{{ url('/songs?genres=true') }}"><h3>Genres</h3></a></div>
            <div><a href="{{ url('/word-cloud') }}"><h3>Word Cloud</h3></a></div>
            <div>
                <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                    <h3>Logout</h3>
                </a>
            </div>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        @endauth
    </div>

</body>

</html>

<script type="text/javascript">
    var APP_URL = {!! json_encode(url('/')) !!}
</script>

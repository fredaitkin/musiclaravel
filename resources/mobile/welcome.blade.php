<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }
            body {
                background: url({{ asset('img/skeleton_no_border.jpg') }});
                background-size: cover;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 15px;
                top: 40px;
            }
            .top-right a {
                color: peru !important;
            }
             .top-right a::after {
                content: "\a";
                white-space: pre;
            }
            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
                color: white;
                font-weight: bold;
                font-style: italic;
                margin-bottom: 400px;
            }

            .links > a {
                color: black;
                padding-right: 5px;
                font-size: 16px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body background='storage/skelton.jpg'>
        <div class="flex-center position-ref full-height">
            <div class="top-right links">
                <a href="{{ url('/songs') }}">Songs</a>
                <a href="{{ url('/artists') }}">Artists</a>
                <a href="{{ url('/playlists') }}">Playlists</a>
                <a href="{{ url('/songs?genres=true') }}">Genres</a>
            </div>

            <div>
                <div class="title">
                    Music
                </div>
            </div>
        </div>
    </body>
</html>

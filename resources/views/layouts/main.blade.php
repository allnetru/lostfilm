<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title')</title>

        <!-- Styles -->
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" rel="stylesheet" type="text/css">
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: sans-serif;
                margin: 10px;
            }
        </style>
    </head>
    <body>
        <div class="content">
            @yield('content')
        </div>
    </body>
</html>

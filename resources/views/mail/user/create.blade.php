<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
Dear user <br>
You user account has been created, you can log into the system using the following details <br>
Website: <a href="{{ url('/login') }}">Login</a> <br>
User: {{ $user->email }} <br>
Password: {{ $password }} <br>
</body>
</html>

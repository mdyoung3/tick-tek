<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-zinc-950 antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Tick-tek') }}</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">


    @vite(['resources/js/app.js', 'resources/css/app.css'])
</head>
<body class="font-sans bg-zinc-950 min-h-screen">
    <div id="app"></div>
</body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-900 text-slate-100 antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Tick-tek') }}</title>

    @vite(['resources/js/app.js', 'resources/css/app.css'])
</head>
<body class="font-sans">
    <main class="items-center justify-center p-8 bg-gradient-to-br from-slate-900 to-slate-800">
        <div id="app"></div>
    </main>
</body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        {{-- Favicons --}}
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/favicons/apple-touch-icon.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/favicons/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/favicons/favicon-16x16.png') }}">
        <link rel="manifest" href="{{ asset('assets/favicons/site.webmanifest') }}">
        <link rel="mask-icon" href="{{ asset('assets/favicons/safari-pinned-tab.svg') }}" color="#5bbad5">

        <title>{{ config('app.name') }}</title>
        @vite('resources/src/main.ts')
    </head>
    <body>
        <div id="app"></div>
    </body>
</html>

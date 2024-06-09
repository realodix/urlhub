<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') | {{ config('app.name') }}</title>

    @livewireStyles
    @vite(['resources/css/main.css', 'resources/js/app.js'])
</head>

<body class="backend">
    @include('partials.header')

    <main class="main">
        @yield('content')
    </main>

    @livewireScripts
</body>

</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') | {{config('app.name')}}</title>

    @livewireStyles
    <link rel="stylesheet" media="all" href="{!! mix('css/main.css') !!}"/>
    <link rel="stylesheet" media="all" href="{!! mix('css/backend.css') !!}"/>
</head>

<body class="backend">
    @include('partials.nav-header')

    <main class="main">
        @yield('content')
    </main>

    <script src="{!! mix('js/manifest.js') !!}"></script>
    <script src="{!! mix('js/vendor.js') !!}"></script>
    <script src="{!! mix('js/backend.js') !!}"></script>
    @livewireScripts
</body>

</html>

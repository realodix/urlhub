<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>@yield('title') | {{ config('app.name') }}</title>

    @vite(['resources/css/main.css', 'resources/js/app.js'])
</head>

<body class="@yield('css_class')">
    @yield('content')
</body>

</html>

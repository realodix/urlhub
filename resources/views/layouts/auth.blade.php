<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>@yield('title') | {{config('app.name')}}</title>

    <link rel="stylesheet" media="all" href="{!! mix('css/main.css') !!}" />
    <link rel="stylesheet" media="all" href="{!! mix('css/frontend.css') !!}" />
</head>

<body class="@yield('css_class')">

    @yield('content')

    <script src="{!! mix('js/manifest.js') !!}"></script>
    <script src="{!! mix('js/vendor.js') !!}"></script>
    <script src="{!! mix('js/frontend.js') !!}"></script>
</body>

</html>

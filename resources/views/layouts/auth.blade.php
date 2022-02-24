<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<title>@yield('title') | {{appName()}}</title>

{!! style(mix('css/main.css')) !!}
{!! style(mix('css/frontend.css')) !!}
</head>

<body class="@yield('css_class')">

@yield('content')

{!! script(mix('js/manifest.js')) !!}
{!! script(mix('js/vendor.js')) !!}
{!! script(mix('js/frontend.js')) !!}

</body>
</html>

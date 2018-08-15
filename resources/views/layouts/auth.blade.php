<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<title>{{config('app.name')}}</title>

<link rel="stylesheet" href="{{ asset('css/bootstrap-custom.css') }}">
<link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>

<body class="@yield('css_class')">
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container">
    <a class="navbar-brand" href="{{ url('/') }}">{{config('app.name')}}</a>
  </div>
</nav>

@yield('content')

<script src="{{ asset('js/app.js') }}"></script>

</body>
</html>

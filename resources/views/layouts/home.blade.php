<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>{{config('app.name')}}</title>

<!-- Fonts -->
<link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

<link href="{{ asset('css/bootstrap-purple.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{ asset('vendor/fontawesome/css/all.min.css') }}">
<link href="{{ asset('css/styles.css') }}" rel="stylesheet">
</head>

<body>
<nav class="navbar navbar-expand-lg bg-purple navbar-dark">
  <div class="container">
    <a class="navbar-brand" href="{{ url('/') }}">Plur</a>
  </div>
</nav>

@yield('content')

<script src="{{ asset('js/app.js') }}"></script>

</body>
</html>

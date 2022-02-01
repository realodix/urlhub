<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>@yield('title') | {{appName()}}</title>

  {!! style(mix('css/main.css')) !!}
  {!! style(mix('css/backend.css')) !!}
</head>

<body class="backend">
@include('partials.b-header')

<main class="main max-w-7xl mx-auto py-4 sm:mt-0 sm:px-6 lg:px-8">
  @yield('content')
</main>

@include('partials.b-footer')

{!! script(mix('js/manifest.js')) !!}
{!! script(mix('js/vendor.js')) !!}
{!! script(mix('js/backend.js')) !!}
</body>
</html>

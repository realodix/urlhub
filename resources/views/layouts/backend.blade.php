<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>@yield('title') | {{config('app.name')}}</title>

  {{-- Main styles for this application --}}
  {!! style(mix('css/backend.css')) !!}
</head>

<body class="app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show">
@include('backend.partials.header')

<div class="app-body">
  @include('backend.partials.sidebar')

  <main class="main">
    {!! Breadcrumbs::render() !!}
    <div class="container-fluid">
      @yield('content')
    </div>
  </main>
</div>

@include('backend.partials.footer')

{{-- CoreUI and necessary plugins --}}
{!! script(mix('js/manifest.js')) !!}
{!! script(mix('js/vendor.js')) !!}
{!! script(mix('js/backend.js')) !!}
</body>
</html>

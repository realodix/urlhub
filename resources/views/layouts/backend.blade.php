<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>Dashboard | {{config('app.name')}}</title>
  {{-- Icons --}}
  {!! style('https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.css') !!}
  {{-- Main styles for this application --}}
  {!! style( asset('css/backend.css')) !!}
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

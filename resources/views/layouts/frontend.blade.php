<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{appName().' - '.config('app.description')}}</title>

  {!! style(mix('css/frontend.css')) !!}
</head>

<body class="@yield('css_class')">
<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container">
    <a class="navbar-brand" href="{{ url('/') }}">{{appName()}}</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mx-auto"></ul>
      <ul class="navbar-nav">
      @auth
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <img class="img-avatar" src="{{ Auth::user()->avatar }}" alt="Avatar">
          </a>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
            <li><span class="dropdown-item" href="{{ route('dashboard') }}">
                @lang('Signed in as') {{ Str::title(Auth::user()->name) }}
              </span></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="{{ route('dashboard') }}">
                @lang('Dashboard')
              </a></li>
            <li><a class="dropdown-item" href="{{ route('user.edit', Auth::user()->name) }}">
                @lang('Your Profile')
              </a></li>
            <li><a class="dropdown-item" href="{{ route('user.change-password', Auth::user()->name) }}">
                @lang('Change Password')
              </a></li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <a class="dropdown-item" href="{{ route('logout') }}"
                 onclick="event.preventDefault();
                 document.getElementById('logout-form').submit();">
                {{ __('Sign out') }}
              </a>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
              </form>
            </li>
          </ul>
        </li>
      @else
        <li class="nav-item">
          <a class="nav-link" href="{{ route('login') }}">@lang('Login')</a>
        </li>
        @if (Route::has('register') and Config::get('urlhub.registration'))
          <li class="nav-item">
            <a class="nav-link" href="{{ route('register') }}">@lang('Register')</a>
          </li>
        @endif
      @endauth
      </ul>
    </div>
  </div>
</nav>

@yield('content')

{!! script(mix('js/manifest.js')) !!}
{!! script(mix('js/vendor.js')) !!}
{!! script(mix('js/frontend.js')) !!}
</body>
</html>

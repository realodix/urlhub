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
<nav class="navbar navbar-expand navbar-dark">
  <div class="container">
    <a class="navbar-brand" href="{{ url('/') }}">{{appName()}}</a>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto"></ul>
      <ul class="navbar-nav">
        @auth
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <img class="img-avatar" src="{{ Auth::user()->avatar }}" alt="Avatar">
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <span class="dropdown-item" href="{{ route('dashboard') }}">
                @lang('Signed in as') {{ Str::title(Auth::user()->name) }}
              </span>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="{{ route('dashboard') }}">
                @lang('Dashboard')
              </a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="{{ route('user.edit', Auth::user()->name) }}">
                @lang('Your Profile')
              </a>
              <a class="dropdown-item" href="{{ route('user.change-password', Auth::user()->name) }}">
                @lang('Change Password')
              </a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="{{ route('logout') }}"
                 onclick="event.preventDefault();
                 document.getElementById('logout-form').submit();">
                {{ __('Sign out') }}
              </a>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
              </form>
            </div>
          </li>
        @else
          <li class="nav-item">
            <a class="nav-link" href="{{ route('login') }}">@lang('Login')</a>
          </li>
          @if (Route::has('register') and Config::get('urlhub.public_register'))
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

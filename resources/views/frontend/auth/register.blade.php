@extends('layouts.auth')

@section('title', __('Register'))

@section('css_class', 'auth')

@section('content')
<div class="flex flex-col min-h-screen sm:justify-center items-center pt-6 sm:pt-0">
  <div class="text-uh-blue font-bold text-4xl sm:text-6xl">{{appName()}}</div>

  <div class="w-full sm:max-w-md mt-6 px-12 py-8 bg-white shadow-md overflow-hidden sm:rounded-lg">
    @if ( ! Config::get('urlhub.registration') )
      <p class="text-muted">@lang('Sorry, not allowed to register by administrator')</p>
    @else
      <form method="post" action="{{ route('register') }}" aria-label="@lang('Register')">
      @csrf
        <label class="text-slate-700">@lang('Username')</label>
        <input class="form-input mt-1" id="name" type="text" name="name" required autofocus>

        @if ($errors->has('name'))
          <strong class="text-red-500">{{ $errors->first('name') }}hhhh</strong>
        @endif

        <div class="mt-4"></div>

        <label class="text-slate-700">@lang('Email')</label>
        <input class="form-input mt-1" id="email" type="email" name="email" required>

        @if ($errors->has('email'))
          <strong class="text-red-500">{{ $errors->first('email') }}</strong>
        @endif

        <div class="mt-4"></div>

        <label class="text-slate-700">@lang('Password')</label>
        <input class="form-input mt-1" id="password" type="password" name="password" required>

        @if ($errors->has('password'))
          <strong class="text-red-500">{{ $errors->first('password') }}</strong>
        @endif

        <div class="mt-4"></div>

        <label class="text-slate-700">@lang('Password')</label>
        <input class="form-input mt-1" id="password-confirm" type="password" name="password_confirmation" required>

        <div class="flex items-center justify-end mt-8">
          <button type="submit" class="button">@lang('Create Account')</button>
        </div>
      </form>
    @endif
  </div>
</div>
@endsection

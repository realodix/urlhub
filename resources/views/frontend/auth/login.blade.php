@extends('layouts.auth')

@section('title', __('Login'))

@section('css_class', 'auth')

@section('content')
<div class="flex flex-col min-h-screen sm:justify-center items-center pt-6 sm:pt-0">

  <div class="text-nord-f4 font-bold text-4xl sm:text-6xl">{{appName()}}</div>

  @if(session()->has('login_error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      {{ session()->get('login_error') }}
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  @endif

  <div class="w-full sm:max-w-md mt-6 px-12 py-8 bg-white shadow-md overflow-hidden sm:rounded-lg">
    <form method="POST" action="{{ route('login') }}" aria-label="@lang('Login')">
    @csrf
      <div>
        @if (Request::has('previous'))
          <input type="hidden" name="previous" value="{{ Request::get('previous') }}">
        @else
          <input type="hidden" name="previous" value="{{ URL::previous() }}">
        @endif
        <label class="block font-medium text-sm text-gray-700" for="email">
          @lang('E-Mail / Username')
        </label>
        <input class="form-input mt-1" id="identity" name="identity" type="text" value="{{ old('identity') }}" required autofocus>
      </div>

      <div class="mt-4">
        <label class="block font-medium text-sm text-gray-700" for="password">
          @lang('Password')
        </label>
        <input class="form-input mt-1" id="password" type="password" name="password" required="required" autocomplete="current-password">
      </div>

      <div class="flex items-center justify-end mt-4">
        <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
          @lang('Forgot your password?')'
        </a>

        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition ml-4">
          @lang('Log in')
        </button>
      </div>
    </form>
  </div>

</div>
@endsection

@extends('layouts.auth')

@section('title', __('Reset Password'))

@section('css_class', 'auth')

@section('content')
<div class="flex flex-col min-h-screen sm:justify-center items-center pt-6 sm:pt-0">
  <div class="w-full sm:max-w-md mt-6 p-4 text-center">
    <h2 class="text-3xl">@lang('Reset Password')</h2>
  </div>

  <div class="w-full sm:max-w-md px-12 py-8 bg-white shadow-md overflow-hidden sm:rounded-lg">
    @if ($errors->has('email'))
      <span class="font-light text-red-500">
        {{$errors->first('email')}}
      </span>
    @endif

    <form method="POST" action="{{ route('password.email') }}" aria-label="@lang('Reset Password')" class="mt-4">
    @csrf

      <label class="block font-medium text-sm text-slate-700" for="email">
        @lang('E-Mail')
      </label>
      <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-input mt-1" required>

      <div class="flex items-center justify-end mt-4">
        <button type="submit" class="button">
          @lang('Send Password Reset Link')
        </button>
      </div>
    </form>
  </div>
</div>
@endsection

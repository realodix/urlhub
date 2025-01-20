@extends('layouts.auth')

@section('title', __('Login'))

@section('css_class', 'auth')

@section('content')
<div class="flex flex-col min-h-screen sm:justify-center items-center pt-6 sm:pt-0">
    <div class="logo text-4xl sm:text-6xl">
        <a href="{{ url('/') }}">{{ config('app.name') }}</a>
    </div>

    @if(session()->has('login_error'))
        <div class="alert alert-error mt-4" role="alert">
            {{ session()->get('login_error') }}
        </div>
        {{ session()->forget('login_error') }}
    @endif

    <div class="auth-card">
        <form method="POST" action="{{ route('login') }}" aria-label="{{ __('Login') }}" class="space-y-4">
        @csrf
            <div>
                <label class="form-label">
                    {{ __('E-Mail / Username') }}
                </label>
                <input name="identity" required value="{{ old('identity') }}" id="identity" class="form-input mt-1" autofocus>
            </div>

            <div>
                <label class="form-label">
                    {{ __('Password') }}
                </label>
                <input type="password" name="password" required autocomplete="current_password" class="form-input mt-1">
            </div>

            <div class="flex items-center justify-end">
                <a href="{{ route('password.request') }}" class="text-primary-700 hover:text-primary-500 font-medium">
                    {{ __('Forgot password?') }}
                </a>
            </div>

            <button type="submit" class="btn btn-primary w-full">
                {{ __('Log in') }}
            </button>
        </form>

        @if (Route::has('register') and Config::get('urlhub.registration'))
        <div class="mt-6 text-center text-sm text-gray-600">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-primary-700 hover:text-primary-500 font-medium">Sign up</a>
        </div>
        @endif
    </div>
</div>
@endsection

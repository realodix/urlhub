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
        <form method="POST" action="{{ route('login') }}" aria-label="{{ __('Login') }}">
        @csrf
            <div>
                <label for="email" class="form-label">
                    {{ __('E-Mail / Username') }}
                </label>
                <input name="identity" class="form-input mt-1" value="{{ old('identity') }}" required autofocus>
            </div>

            <div class="mt-4">
                <label for="password" class="form-label">
                    {{ __('Password') }}
                </label>
                <input type="password" name="password" class="form-input mt-1" autocomplete="current_password" required>
            </div>

            <div class="flex items-center justify-end mt-4">
                <a href="{{ route('password.request') }}" class="text-sm text-slate-600 hover:text-slate-900 underline">
                    {{ __('Forgot password?') }}
                </a>

                <button type="submit" class="btn btn-primary ml-4">
                    {{ __('Log in') }}
                </button>
            </div>
        </form>
    </div>

    @if (Route::has('register') and Config::get('urlhub.registration'))
    <div class="auth-card">
        New to {{ config('app.name') }}? <a href="{{ route('register') }}" class="text-slate-600 hover:text-slate-900 underline">Create an account</a>
    </div>
    @endif

</div>
@endsection

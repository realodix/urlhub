@extends('layouts.auth')

@section('title', __('Register'))

@section('css_class', 'auth')

@section('content')
<div class="flex flex-col min-h-screen sm:justify-center items-center pt-6 sm:pt-0">
    <div class="logo text-4xl sm:text-6xl">
        <a href="{{ url('/') }}">{{ config('app.name') }}</a>
    </div>

    <div class="auth-card">
        @if (!config('urlhub.registration') )
            <p class="text-muted">{{ __('Sorry, not allowed to register by administrator') }}</p>
        @else
            <form method="post" action="{{ route('register') }}" aria-label="{{ __('Register') }}" class="space-y-4">
            @csrf
                <div>
                    <label class="form-label">{{ __('Username') }}</label>
                    <input name="name" class="form-input mt-1" id="name" required autofocus>

                    @if ($errors->has('name'))
                        <strong class="text-red-500">{{ $errors->first('name') }}</strong>
                    @endif
                </div>

                <div>
                    <label class="form-label">{{ __('Email') }}</label>
                    <input type="email" name="email" class="form-input mt-1" id="email" required>

                    @if ($errors->has('email'))
                        <strong class="text-red-500">{{ $errors->first('email') }}</strong>
                    @endif
                </div>

                <div>
                    <label class="form-label">{{ __('Password') }}</label>
                    <input type="password" name="password" class="form-input mt-1" id="password" required>

                    @if ($errors->has('password'))
                        <strong class="text-red-500">{{ $errors->first('password') }}</strong>
                    @endif
                </div>

                <div>
                    <label class="form-label">{{ __('Password Confirmation') }}</label>
                    <input type="password" name="password_confirmation" class="form-input mt-1" id="password-confirm" required>
                </div>

                <button type="submit" class="btn btn-primary w-full !mt-6">{{ __('Create Account') }}</button>
            </form>
        @endif
    </div>
</div>
@endsection

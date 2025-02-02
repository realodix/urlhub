@extends('layouts.auth')

@section('title', __('Register'))

@section('css_class', 'auth')

@section('content')
<div class="flex flex-col min-h-screen sm:justify-center items-center pt-6 sm:pt-0">
    <div class="logo text-4xl sm:text-6xl">
        <a href="{{ url('/') }}">{{ config('app.name') }}</a>
    </div>

    <div class="auth-box card">
        @if (settings()->anyone_can_register === false)
            <p class="text-muted">{{ __('Sorry, we are closed for registrations at this time.') }}</p>
        @else
            <form method="post" action="{{ route('register') }}" aria-label="{{ __('Register') }}" class="space-y-4">
            @csrf
                <div>
                    <label class="form-label">{{ __('Username') }}</label>
                    <input name="name" required class="form-input mt-1" autofocus>

                    @if ($errors->has('name'))
                        <strong class="text-red-500">{{ $errors->first('name') }}</strong>
                    @endif
                </div>

                <div>
                    <label class="form-label">{{ __('Email') }}</label>
                    <input type="email" name="email" required class="form-input mt-1">

                    @if ($errors->has('email'))
                        <strong class="text-red-500">{{ $errors->first('email') }}</strong>
                    @endif
                </div>

                <div>
                    <label class="form-label">{{ __('Password') }}</label>
                    <input type="password" name="password" required class="form-input mt-1">

                    @if ($errors->has('password'))
                        <strong class="text-red-500">{{ $errors->first('password') }}</strong>
                    @endif
                </div>

                <div>
                    <label class="form-label">{{ __('Password Confirmation') }}</label>
                    <input type="password" name="password_confirmation" required class="form-input mt-1">
                </div>

                <button type="submit" class="btn btn-primary w-full !mt-6">{{ __('Create Account') }}</button>
            </form>
        @endif
    </div>
</div>
@endsection

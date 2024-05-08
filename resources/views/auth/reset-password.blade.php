@extends('layouts.auth')

@section('title', __('Reset Password'))

@section('css_class', 'auth')

@section('content')
<div class="flex flex-col min-h-screen sm:justify-center items-center pt-6 sm:pt-0">
    @if ($errors->any())
        <div>
            <div>{{ __('Whoops! Something went wrong.') }}</div>

            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="auth-card">
        <form method="POST" action="{{ route('password.update') }}">
        @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <div>
                <label class="form-label">{{ __('Email') }}</label>
                <input type="email" name="email" value="{{ old('email', $email) }}" class="form-input mt-1" required autofocus/>
            </div>

            <div class="mt-4">
                <label class="form-label">{{ __('Password') }}</label>
                <input type="password" name="password" autocomplete="new-password" class="form-input mt-1" required/>
            </div>

            <div class="mt-4">
                <label class="form-label">{{ __('Confirm Password') }}</label>
                <input type="password" name="password_confirmation" autocomplete="new-password" class="form-input mt-1" required/>
            </div>

            <div class="flex items-center justify-center mt-8">
                <button type="submit" class="btn btn-primary ml-4">
                    {{ __('Reset Password') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

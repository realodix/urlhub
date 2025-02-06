@extends('layouts.auth')

@section('title', __('Forgot Password'))

@section('css_class', 'auth')

@section('content')
<div class="flex flex-col min-h-screen sm:justify-center items-center pt-6 sm:pt-0">
    @if (session('status'))
        <div>
            {{ session('status') }}
        </div>
    @endif
    @include('partials/messages')

    <div class="auth-box card">
        <p class="mb-1">{{ __('Forgot your password? No problem.') }}
        <p class="text-sm text-gray-600 dark:text-dark-400">
            {{ __('Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </p>

        <br>

        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf
            <div>
                <label class="form-label">{{ __('Email') }}</label>
                <input type="email" name="email" required value="{{ old('email') }}" class="form-input" autofocus>
            </div>

            <button type="submit" class="btn btn-primary w-full">
                {{ __('Email Password Reset Link') }}
            </button>
        </form>
    </div>
</div>
@endsection

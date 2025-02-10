@extends('layouts.auth')

@section('title', __('Forgot Password'))

@section('css_class', 'auth')

@section('content')
<div class="flex flex-col min-h-screen sm:justify-center items-center pt-6 sm:pt-0">
    <a href="{{ url('/') }}">
        <div class="absolute top-2 left-2 flex justify-center items-center hover:underline">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-left h-5 w-5"><path d="m15 18-6-6 6-6"></path></svg>
            <p class="">Back</p>
        </div>
    </a>

    @if (session('status'))
        <div>
            {{ session('status') }}
        </div>
    @endif
    @include('partials/messages')

    <div class="auth-box card">
        <h1 class="text-2xl font-extrabold tracking-tight lg:text-5xl mb-6">Forgot Password</h1>
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

        <div class="text-center mt-4">
            <p>Know your password? <a class="text-primary-700 dark:text-primary-600 hover:text-primary-500 dark:hover:text-primary-600/90 font-medium" href="{{ route('login') }}">Login</a></p>
        </div>
    </div>
</div>
@endsection

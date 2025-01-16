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

    @if ($errors->any())
        <div class="alert alert-error">
            <div class="font-bold">{{ __('Whoops! Something went wrong.') }}</div>

            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="auth-card">
        <p class="mb-1">{{ __('Forgot your password? No problem.') }}
        <p class="text-gray-600 text-sm">
            {{ __('Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </p>

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

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
        <p class="font-bold">{{ __('Forgot your password? No problem.') }}
        <p class="text-slate-600">
            {{ __('Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </p>

        <br>

        <form method="POST" action="{{ route('password.email') }}">
        @csrf
            <div>
                <label class="form-label">{{ __('Email') }}</label>
                <input type="email" name="email" class="form-input" id="email" value="{{ old('email') }}" required autofocus>
            </div>

            <div class="flex items-center justify-center mt-8">
                <button type="submit" class="btn btn-primary ml-4">
                    {{ __('Email Password Reset Link') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

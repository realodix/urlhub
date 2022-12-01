@extends('layouts.auth')

@section('title', __('Login'))

@section('css_class', 'auth')

@section('content')
    <div class="flex flex-col min-h-screen sm:justify-center items-center pt-6 sm:pt-0">
        <div class="text-center mb-4">
            {{ __('Forgot your password? No problem.') }} <br>
            {{ __('Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </div>

        @if (session('status'))
            <div>
                {{ session('status') }}
            </div>
        @endif

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

        <form method="POST" action="{{ route('password.email') }}">
        @csrf
            <div>
                <label class="block font-medium text-sm text-slate-700">{{ __('Email') }}</label>
                <input type="email" name="email" class="form-input" id="email" value="{{ old('email') }}" required autofocus>
            </div>

            <div class="flex items-center justify-center mt-4">
                <button type="submit" class="btn btn-primary ml-4">
                    {{ __('Email Password Reset Link') }}
                </button>
            </div>
        </form>
    </div>
@endsection

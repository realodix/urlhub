@extends('layouts.auth')

@section('title', __('Reset Password'))

@section('css_class', 'auth')

@section('content')
<div class="flex flex-col min-h-screen sm:justify-center items-center pt-6 sm:pt-0">
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
        <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
        @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <div>
                <label class="form-label">{{ __('Email') }}</label>
                <input type="email" name="email" value="{{ old('email', $email) }}" class="form-input mt-1" required autofocus/>
            </div>

            <div>
                <label class="form-label">{{ __('Password') }}</label>
                <input type="password" name="password" autocomplete="new_password" class="form-input mt-1" required/>
            </div>

            <div>
                <label class="form-label">{{ __('Confirm Password') }}</label>
                <input type="password" name="password_confirmation" autocomplete="new_password" class="form-input mt-1" required/>
            </div>

            <button type="submit" class="btn btn-primary w-full !mt-6">
                {{ __('Reset Password') }}
            </button>
        </form>
    </div>
</div>
@endsection

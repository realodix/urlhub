@extends('layouts.auth')

@section('title', __('Login'))

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
        <form method="POST" action="{{ route('password.confirm') }}">
        @csrf
            <div>
                <label>{{ __('Password') }}</label>
                <input type="password" name="password" autocomplete="current_password" class="form-input" required>
            </div>

            <div class="flex items-center justify-end mt-4">
                <button type="submit" class="btn btn-primary ml-4">
                    {{ __('Confirm Password') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@extends('layouts.auth')

@section('title', __('Login'))

@section('css_class', 'auth')

@section('content')
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

    <form method="POST" action="{{ route('password.confirm') }}">
    @csrf
        <div>
            <label>{{ __('Password') }}</label>
            <input type="password" name="password" autocomplete="current-password" required>
        </div>

        <div>
            <button type="submit">
                {{ __('Confirm Password') }}
            </button>
        </div>

        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}">
                {{ __('Forgot Your Password?') }}
            </a>
        @endif
    </form>
@endsection

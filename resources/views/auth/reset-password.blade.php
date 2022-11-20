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

    <form method="POST" action="{{ route('password.update') }}">
    @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div>
        	<label>{{ __('Email') }}</label>
        	<input type="email" name="email" value="{{ old('email', $email) }}" required autofocus/>
        </div>

        <div>
            <label>{{ __('Password') }}</label>
            <input type="password" name="password" autocomplete="new-password" required/>
        </div>

        <div>
            <label>{{ __('Confirm Password') }}</label>
            <input type="password" name="password_confirmation" autocomplete="new-password" required/>
        </div>

        <div>
            <button type="submit">
                {{ __('Reset Password') }}
            </button>
        </div>
    </form>
@endsection

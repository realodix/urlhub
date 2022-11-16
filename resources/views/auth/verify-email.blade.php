@extends('layouts.auth')

@section('title', __('Login'))

@section('css_class', 'auth')

@section('content')
    <div>
        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div>
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
    @csrf

        <button type="submit">
            {{ __('Resend Verification Email') }}
        </button>
    </form>

    <form method="POST" action="{{ route('logout') }}">
    @csrf

        <button type="submit">
            {{ __('Logout') }}
        </button>
    </form>
@endsection

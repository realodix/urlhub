@extends('layouts.auth')

@section('title', __('Login'))

@section('css_class', 'auth')

@section('content')
<div class="flex flex-col min-h-screen sm:justify-center items-center pt-6 sm:pt-0">
    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success !mb-10">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="common-card-style mb-8">
        <p class="font-bold">
            {{ __('Thanks for signing up!') }}
        </p>
        <p class="text-gray-600">
            {{ __('Before getting started, could you verify your email address by clicking on the link we just emailed to you?') }}
        </p>
        <p class="text-gray-600">
            {{ __('If you didn\'t receive the email, we will gladly send you another.') }}
        </p>
    </div>

    <form method="POST" action="{{ route('verification.send') }}">
    @csrf
        <button type="submit" class="btn btn-primary mt-4">
            {{ __('Resend Verification Email') }}
        </button>
    </form>

    <form method="POST" action="{{ route('logout') }}">
    @csrf
        <button type="submit" class="btn btn-primary mt-4">
            {{ __('Logout') }}
        </button>
    </form>
</div>
@endsection

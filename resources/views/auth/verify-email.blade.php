@extends('layouts.general')

@section('title', 'Login')
@section('css_class', 'auth')
@section('content')
<div class="flex flex-col min-h-screen sm:justify-center items-center pt-6 sm:pt-0">
    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success mb-10!">
            A new verification link has been sent to the email address you provided during registration.
        </div>
    @endif

    <div class="content-container card card-master mb-8">
        <p class="font-bold">
            Thanks for signing up!
        </p>
        <p class="text-slate-600">
            Before getting started, could you verify your email address by clicking on the link we just emailed to you?
        </p>
        <p class="text-slate-600">
            If you didn\'t receive the email, we will gladly send you another.
        </p>
    </div>

    <form method="POST" action="{{ route('verification.send') }}">
    @csrf
        <button type="submit" class="btn btn-secondary mt-4">
            Resend Verification Email
        </button>
    </form>

    <form method="POST" action="{{ route('logout') }}">
    @csrf
        <button type="submit" class="btn btn-primary mt-4">
            Logout
        </button>
    </form>
</div>
@endsection

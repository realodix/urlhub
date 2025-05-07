@extends('layouts.general')

@section('title', 'Login')
@section('css_class', 'auth')
@section('content')
<div class="flex flex-col min-h-screen sm:justify-center items-center pt-6 sm:pt-0">
    @if ($errors->any())
        <div class="alert alert-error">
            <div class="font-bold">Whoops! Something went wrong.</div>

            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="auth-box card">
        <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
        @csrf
            <div>
                <label>Password</label>
                <input type="password" name="password" required autocomplete="current_password" class="form-input">
            </div>

            <button type="submit" class="btn btn-primary">
                Confirm Password
            </button>
        </form>
    </div>
</div>
@endsection

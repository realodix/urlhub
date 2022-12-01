@extends('layouts.auth')

@section('title', __('Login'))

@section('css_class', 'auth')

@section('content')
    <div class="flex flex-col min-h-screen sm:justify-center items-center pt-6 sm:pt-0">

        <div class="font-bold text-uh-blue text-4xl sm:text-6xl">{{config('app.name')}}</div>

        @if(session()->has('login_error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session()->get('login_error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {{ session()->forget('login_error') }}
        @endif

        <div class="common-card-style w-full sm:max-w-md mt-6 px-12 py-8 overflow-hidden">
            <form method="POST" action="{{ route('login') }}" aria-label="{{__('Login')}}">
            @csrf
                <div>
                    @if (Request::has('previous'))
                        <input type="hidden" name="previous" value="{{ Request::get('previous') }}">
                    @else
                        <input type="hidden" name="previous" value="{{ URL::previous() }}">
                    @endif
                    <label for="email" class="block font-medium text-sm text-slate-700">
                        {{__('E-Mail / Username')}}
                    </label>
                    <input type="text" name="identity" class="form-input mt-1" value="{{ old('identity') }}" required autofocus>
                </div>

                <div class="mt-4">
                    <label for="password" class="block font-medium text-sm text-slate-700">
                        {{__('Password')}}
                    </label>
                    <input type="password" name="password" class="form-input mt-1" autocomplete="current-password" required>
                </div>

                <div class="flex items-center justify-end mt-4">
                    <a href="{{ route('password.request') }}" class="text-sm text-slate-600 hover:text-slate-900 underline">
                        {{__('Forgot your password?')}}
                    </a>

                    <button type="submit" class="btn btn-primary ml-4">
                        {{__('Log in')}}
                    </button>
                </div>
            </form>
        </div>

    </div>
@endsection

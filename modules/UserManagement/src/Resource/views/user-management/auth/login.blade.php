@extends('user-management.auth.master')

@section('header')
    @parent

@endsection

@section('content')

    <h4>{{ __('trans.get_start') }}</h4>
    <h6 class="font-weight-light">{{ __('trans.sign_in_to_continue') }}</h6>

    <form class="pt-3" action="{{ route('auth.user.login') }}" method="post">
        {{ csrf_field() }}
        
        <div class="form-group">
            <input type="{{ (config('laravel_user_management.auth.username') == 'mobile' ) ? 'text' : 'email' }}" name="{{ config('laravel_user_management.auth.username') }}" class="form-control form-control-lg" placeholder="{{ __('trans.placeholders.username') }}" required>
        </div>
        <div class="form-group">
            <input type="password" name="password" class="form-control form-control-lg" placeholder="{{ __('trans.placeholders.password') }}" required>
        </div>
        <div class="mt-3">
            <button type="submit" class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn" >
                {{ __('trans.sign_in') }}
            </button>
        </div>
        <div class="my-2 d-flex justify-content-between align-items-center">
            {{-- <a href="#" class="auth-link text-black">{{ __('trans.Forgot_password') }}</a> --}}
        </div>

        <div class="text-center mt-4 font-weight-light">
            {{ __('trans.do_you_have_account') }} <a href="{{ route('auth.user.register') }}" class="text-primary">
            {{__('trans.create') }}
        </a>
        </div>
    </form>

@endsection


@section('footer')
    @parent

@endsection
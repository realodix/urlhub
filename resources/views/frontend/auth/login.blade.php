@extends('layouts.auth')

@section('title', __('Login'))

@section('css_class', 'auth')

@section('content')
<div class="container">
<div class="row justify-content-center align-items-center" style="min-height: 100vh;">
<div class="col-md-8">

  @if(session()->has('login_error'))
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session()->get('login_error') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  @endif

  <div class="card-group">
    <div class="card p-4">
      <div class="card-body">
        <h1>@lang('Login')</h1>
        <p class="text-muted">@lang('Sign In to your account')</p>
        <form method="POST" action="{{ route('login') }}" aria-label="@lang('Login')">
        @csrf
          <div class="input-group mb-3">
            @if (Request::has('previous'))
              <input type="hidden" name="previous" value="{{ Request::get('previous') }}">
            @else
              <input type="hidden" name="previous" value="{{ URL::previous() }}">
            @endif
            <span class="input-group-text"><i class="fas fa-user"></i></span>
            <input id="identity" type="text" class="form-control{{ $errors->has('identity') ? ' is-invalid' : '' }}" name="identity" value="{{ old('identity') }}" placeholder="@lang('E-Mail / Username')" required autofocus>
          </div>
          <div class="input-group mb-4">
            <span class="input-group-text"><i class="fas fa-lock"></i></span>
            <input class="form-control" type="password" placeholder="@lang('Password')" name="password"/></div>
          <div class="row">
            <div class="col-6"><a href="{{ route('password.request') }}" class="px-0 text-decoration-none">@lang('Forgot password?')</a></div>
            <div class="col-6 text-right"><button class="btn btn-success px-4" type="submit">@lang('Login')</button></div>
          </div>
        </form>
      </div>
    </div>
	@if ( Config::get('urlhub.registration' ))
    <div class="card bg-success text-white py-5 d-none d-md-block" style="width:44%;">
      <div class="card-body text-center">
        <div>
          <h2>@lang("Don't have an account?")</h2>
          <a class="btn btn-light active mt-3" href="{{ route('register') }}">@lang('Register Now!')</a>
        </div>
      </div>
    </div>
	@endif
  </div>

</div>
</div>
</div>
@endsection

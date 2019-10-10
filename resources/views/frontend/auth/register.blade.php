@extends('layouts.auth')

@section('title', __('Register'))

@section('css_class', 'auth-register')

@section('content')
<div class="container">
<div class="row justify-content-center align-items-center" style="min-height: 100vh;">

<div class="col-md-6">
<div class="card mx-4">
  <div class="card-body p-4">
    <form method="post" action="{{ route('register') }}" aria-label="@lang('Register')">
    @csrf
      <h1>@lang('Register')</h1>
      <p class="text-muted">@lang('Create your account')</p>

      <div class="input-group mb-3">
        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-user"></i></span></div>
        <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" placeholder="@lang('Username')" required autofocus>

        @if ($errors->has('name'))
        <span class="invalid-feedback" role="alert">
          <strong>{{ $errors->first('name') }}</strong>
        </span>
        @endif
      </div>
      <div class="input-group mb-3">
        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-at"></i></span></div>
        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" placeholder="@lang('Email')" required>

        @if ($errors->has('email'))
        <span class="invalid-feedback" role="alert">
          <strong>{{ $errors->first('email') }}</strong>
        </span>
        @endif
      </div>
      <div class="input-group mb-3">
        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-lock"></i></span></div>
        <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="@lang('Password')" required>

        @if ($errors->has('password'))
        <span class="invalid-feedback" role="alert">
          <strong>{{ $errors->first('password') }}</strong>
        </span>
        @endif
      </div>
      <div class="input-group mb-4">
        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-lock"></i></span></div>
        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="@lang('Repeat password')" required>
      </div>

      <button class="btn btn-block btn-success" type="submit">@lang('Create Account')</button>
    </form>
  </div>
</div>
</div>

</div>
</div>
@endsection

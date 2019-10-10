@extends('layouts.auth')

@section('title', __('Reset Password'))

@section('css_class', 'auth-email')

@section('content')
<div class="container">
<div class="row justify-content-center mt-5">
<div class="col-md-8">
  <div class="card">
    <div class="card-header"><b>@lang('Reset Password')</b></div>

    <div class="card-body">
      @if (session('status'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('status') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      @endif

      <form method="POST" action="{{ route('password.email') }}" aria-label="@lang('Reset Password')">
      @csrf

        <div class="input-group mb-3">
          <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-at"></i></span></div>
          <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" placeholder="@lang('E-Mail Address')" required>

          @if ($errors->has('email'))
          <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first('email') }}</strong>
          </span>
          @endif
        </div>

        <div class="form-group row mb-0">
          <div class="col-md-6 offset-md-4">
            <button type="submit" class="btn btn-primary">
              @lang('Send Password Reset Link')
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
</div>
</div>
@endsection

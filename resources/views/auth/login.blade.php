@extends('layouts.auth')

@section('content')
<div class="container">
<div class="row justify-content-center mt-5">
<div class="col-md-8">

  @if(session()->has('login_error'))
  <div class="alert alert-danger">
    {{ session()->get('login_error') }}
  </div>
  @endif

  <div class="card">
    <div class="card-header">{{ __('Login') }}</div>

    <div class="card-body">
      <form method="POST" action="{{ route('login') }}" aria-label="{{ __('Login') }}">
      @csrf

        <div class="form-group row">
          @if (Request::has('previous'))
            <input type="hidden" name="previous" value="{{ Request::get('previous') }}">
          @else
            <input type="hidden" name="previous" value="{{ URL::previous() }}">
          @endif
          
          <label for="identity" class="col-sm-4 col-form-label text-md-right">{{ __('E-Mail / Username') }}</label>

          <div class="col-md-6">
            <input id="identity" type="text" class="form-control{{ $errors->has('identity') ? ' is-invalid' : '' }}" name="identity" value="{{ old('identity') }}" required autofocus>

            @if ($errors->has('identity'))
            <span class="invalid-feedback" role="alert">
              <strong>{{ $errors->first('identity') }}</strong>
            </span>
            @endif
          </div>
        </div>

        <div class="form-group row">
          <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

          <div class="col-md-6">
            <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

            @if ($errors->has('password'))
            <span class="invalid-feedback" role="alert">
              <strong>{{ $errors->first('password') }}</strong>
            </span>
            @endif
          </div>
        </div>

        <div class="form-group row">
          <div class="col-md-6 offset-md-4">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

              <label class="form-check-label" for="remember">
                {{ __('Remember Me') }}
              </label>
            </div>
          </div>
        </div>

        <div class="form-group row mb-0">
          <div class="col-md-8 offset-md-4">
            <button type="submit" class="btn btn-primary">
              {{ __('Login') }}
            </button>

            <a class="btn btn-link" href="{{ route('password.request') }}">
              {{ __('Forgot Your Password?') }}
            </a>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
</div>
</div>
@endsection

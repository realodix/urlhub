@extends('layouts.backend')

@section('title', Str::title(Auth::user()->name) .' ‹ '. __('Change Password'))

@section('content')

@include('messages')

<div class="row">
  <div class="col-xl-4">
    <div class="px-4 sm:px-0">
        <h3>@lang('Change Password')</h3>

        <p class="mt-1 fw-light">
          @lang('Ensure your account is using a long, random password to stay secure.')
        </p>
    </div>
  </div>
  <div class="col-xl-6">
    <form method="post" action="{{route('user.change-password.post', $user->getRouteKey())}}">
    @csrf
      <div class="card">
        <div class="card-body">
          <div class="row mt-4 mb-4">
          <div class="col">
            <div class="input-group mb-3{{ $errors->has('current-password') ? ' has-error' : '' }} row">
              <label for="current-password" class="col-sm-3 col-form-label">@lang('Your Password')</label>

              <div class="col">
                <input id="current-password" type="password" class="form-control" name="current-password" placeholder="Enter your password" required>
              </div>
            </div>

            <div class="input-group mb-3{{ $errors->has('new-password') ? ' has-error' : '' }} row">
              <label for="new-password" class="col-sm-3 col-form-label">@lang('New Password')</label>

              <div class="col">
                <div class="input-group password-toggler-container">
                  <input type="password" class="form-control" id="new-password" name="new-password" aria-label="Enter a new password" placeholder="Enter a new password" required>
                  <span class="input-group-text password-toggler">
                    <i class="fa fa-eye-slash"></i>
                    <i class="fa fa-eye d-none"></i>
                  </span>
                </div>
              </div>
            </div>

            <div class="input-group mb-3 row">
              <label for="new-password-confirm" class="col-sm-3 col-form-label">@lang('Confirmation')</label>

              <div class="col">
                <div class="input-group password-toggler-container">
                  <input type="password" class="form-control" id="new-password-confirm" name="new-password_confirmation" aria-label="Retype the new password" placeholder="Retype the new password" required>
                  <span class="input-group-text password-toggler">
                    <i class="fa fa-eye-slash"></i>
                    <i class="fa fa-eye d-none"></i>
                  </span>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col text-end">
                <button type="submit" class="btn btn-primary">
                  @lang('Change Password')
                </button>
              </div>
            </div>
          </div><!--col-->
          </div><!--row-->
        </div><!--card-body-->
      </div><!--card-->
    </form>
  </div>
</div>
@endsection

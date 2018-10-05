@extends('layouts.backend')

@section('title', title_case(Auth::user()->name) .' ‹ '. __('Change Password'))

@section('content')

@if (session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
  {{ session('error') }}
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
@endif

@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
  {{ session('success') }}
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
@endif

<div class="row">
  <div class="col-xl-6">
    <form method="post" action="{{route('user.change-password.post', $name)}}">
    @csrf
      <div class="card">
        <div class="card-body">
          <h4 class="card-title mb-0">
            Account Management
            <small class="text-muted">@lang('Change Password')</small>
          </h4>

          <hr />

          <div class="row mt-4 mb-4">
          <div class="col">
            <div class="form-group{{ $errors->has('current-password') ? ' has-error' : '' }} row">
              <label for="current-password" class="col-sm-3 col-form-label">@lang('Current Password')</label>

              <div class="col">
                <input id="current-password" type="password" class="form-control" name="current-password" required>
              </div>
            </div>

            <div class="form-group{{ $errors->has('new-password') ? ' has-error' : '' }} row">
              <label for="new-password" class="col-sm-3 col-form-label">@lang('New Password')</label>

              <div class="col">
                <input id="new-password" type="password" class="form-control" name="new-password" required>

                @if ($errors->has('new-password'))
                <span class="form-error-feedback">
                  {{ $errors->first('new-password') }}
                </span>
                @endif
              </div>
            </div>

            <div class="form-group row">
              <label for="new-password-confirm" class="col-sm-3 col-form-label">@lang('Confirm New Password')</label>

              <div class="col">
                <input id="new-password-confirm" type="password" class="form-control" name="new-password_confirmation" required>
              </div>
            </div>

            <div class="row">
              <div class="col text-right">
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

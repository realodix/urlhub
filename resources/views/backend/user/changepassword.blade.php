@extends('layouts.backend')

@section('content')
@if (session('error'))
<div class="alert alert-danger">
  {{ session('error') }}
</div>
@endif

@if (session('success'))
<div class="alert alert-success">
  {{ session('success') }}
</div>
@endif

<div class="row">
  <div class="col-xl-6">
    {{ html()->form('POST', route('changePassword'))->class('form-horizontal')->open() }}
    <div class="card">
      <div class="card-body">
        <div class="row">
        <div class="col-sm-6">
          <h4 class="card-title mb-0">
            User
            <small class="text-muted">@lang('Change Password')</small>
          </h4>
        </div><!--col-->
        </div><!--row-->

        <hr />

        <div class="row mt-4 mb-4">
        <div class="col">
          <div class="form-group{{ $errors->has('current-password') ? ' has-error' : '' }} row">
            <label for="current-password" class="col-sm-3 col-form-label">@lang('Current Password')</label>

            <div class="col">
              <input id="current-password" type="password" class="form-control" name="current-password" required>

              @if ($errors->has('current-password'))
              <span class="help-block">
                <strong>{{ $errors->first('current-password') }}</strong>
              </span>
              @endif
            </div>
          </div>

          <div class="form-group{{ $errors->has('new-password') ? ' has-error' : '' }} row">
            <label for="new-password" class="col-sm-3 col-form-label">@lang('New Password')</label>

            <div class="col">
              <input id="new-password" type="password" class="form-control" name="new-password" required>

              @if ($errors->has('new-password'))
              <span class="help-block">
                <strong>{{ $errors->first('new-password') }}</strong>
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
        </div><!--col-->
        </div><!--row-->
      </div><!--card-body-->

      <div class="card-footer">
        <div class="row">
          <div class="col text-right">
            <button type="submit" class="btn btn-primary">
              @lang('Change Password')
            </button>
          </div><!--row-->
        </div><!--row-->
      </div><!--card-footer-->
    </div><!--card-->
    {{ html()->form()->close() }}
  </div>
</div>
@endsection

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

<div class="card">
<div class="card-body">
  <div class="row">
  <div class="col-sm-5">
    <h4 class="card-title mb-0">
      User
      <small class="text-muted">Change Password</small>
    </h4>
  </div><!--col-->
  </div><!--row-->

  <hr />

  <div class="row mt-4 mb-4">
  <div class="col">
    <form class="form-horizontal" method="POST" action="{{ route('changePassword') }}">
    @csrf

      <div class="form-group{{ $errors->has('current-password') ? ' has-error' : '' }}">
        <label for="new-password" class="col-md-4 control-label">Current Password</label>

        <div class="col-md-6">
          <input id="current-password" type="password" class="form-control" name="current-password" required>

          @if ($errors->has('current-password'))
          <span class="help-block">
            <strong>{{ $errors->first('current-password') }}</strong>
          </span>
          @endif
        </div>
      </div>

      <div class="form-group{{ $errors->has('new-password') ? ' has-error' : '' }}">
        <label for="new-password" class="col-md-4 control-label">New Password</label>

        <div class="col-md-6">
          <input id="new-password" type="password" class="form-control" name="new-password" required>

          @if ($errors->has('new-password'))
          <span class="help-block">
            <strong>{{ $errors->first('new-password') }}</strong>
          </span>
          @endif
        </div>
      </div>

      <div class="form-group">
        <label for="new-password-confirm" class="col-md-4 control-label">Confirm New Password</label>

        <div class="col-md-6">
          <input id="new-password-confirm" type="password" class="form-control" name="new-password_confirmation" required>
        </div>
      </div>

      <div class="form-group">
        <div class="col-md-6 col-md-offset-4">
          <button type="submit" class="btn btn-primary">
            Change Password
          </button>
        </div>
      </div>
    </form>
  </div><!--col-->
  </div><!--row-->
</div><!--card-body-->
</div><!--card-->
@endsection

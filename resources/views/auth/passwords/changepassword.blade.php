@extends('layouts.home')

@section('css_class', 'backend')

@section('content')
<div class="container change-password">

<div class="row mt-5">
<div class="col-md-9 header">
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

  <div class="title">{{ __('Change password') }}</div>
</div>
</div>

<div class="row mt-3">
<div class="col-md-9 body">
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
</div>
</div>
@endsection

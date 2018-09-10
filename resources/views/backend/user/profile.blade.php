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
    {{ html()->form('POST', route('updateProfile'))->class('form-horizontal')->open() }}
    <div class="card">
      <div class="card-body">
        <div class="row">
        <div class="col-sm-6">
          <h4 class="card-title mb-0">
            Profile
            <small class="text-muted">Edit</small>
          </h4>
        </div><!--col-->
        </div><!--row-->

        <hr />

        <div class="row mt-4 mb-4">
        <div class="col">
          <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }} row">
            <label for="name" class="col-sm-3 col-form-label">@lang('Name')</label>

            <div class="col">
              @role('admin')
                <input value="{{$name}}" id="name" type="text" class="form-control" name="name">
              @else
                <input value="{{$name}}" id="name" type="text" class="form-control" name="name" disabled>
              @endrole

              @if ($errors->has('name'))
              <span class="help-block">
                <strong>{{ $errors->first('name') }}</strong>
              </span>
              @endif
            </div>
          </div>

          <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }} row">
            <label for="email" class="col-sm-3 col-form-label">@lang('E-mail Address')</label>

            <div class="col">
              <input value="{{$email}}" id="email" type="email" class="form-control" name="email">

              @if ($errors->has('email'))
              <span class="help-block">
                <strong>{{ $errors->first('email') }}</strong>
              </span>
              @endif
            </div>
          </div>
        </div><!--col-->
        </div><!--row-->
      </div><!--card-body-->

      <div class="card-footer">
        <div class="row">
          <div class="col text-right">
            <button type="submit" class="btn btn-primary">
              Save
            </button>
          </div><!--row-->
        </div><!--row-->
      </div><!--card-footer-->
    </div><!--card-->
    {{ html()->form()->close() }}
  </div>
</div>
@endsection

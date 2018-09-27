@extends('layouts.backend')

@section('title', title_case(Auth::user()->name) .' ‹ '. __('Edit Profile'))

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
    <form method="post" action="{{route('user.update', Auth::user()->name)}}" class="form-horizontal">
    @csrf
      <div class="card">
        <div class="card-body">
          <h4 class="card-title mb-0">
            Profile
            <small class="text-muted">Edit</small>
          </h4>

          <hr />

          <div class="row mt-4 mb-4">
          <div class="col">
            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }} row">
              <label for="name" class="col-sm-3 col-form-label">@lang('Username')</label>

              <div class="col">
                <input value="{{$name}}" id="name" type="text" class="form-control" name="name" disabled>
                <small class="text-muted"><i>Usernames cannot be changed.</i></small>

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
    </form>
  </div>
</div>
@endsection

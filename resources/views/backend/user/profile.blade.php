@extends('layouts.backend')

@section('title', title_case(Auth::user()->name) .' ‹ '. __('Edit Profile'))

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
    <form method="post" action="{{route('user.update', $user->name)}}">
    @csrf
      <div class="card">
        <div class="card-body">
          <h4 class="card-title mb-0">
            @lang('Profile')
            <small class="text-muted">@lang('Edit')</small>
          </h4>

          <hr />

          <div class="row mt-4 mb-4">
          <div class="col">
            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }} row">
              <label for="name" class="col-sm-3 col-form-label">@lang('Username')</label>

              <div class="col">
                <input value="{{$user->name}}" id="name" type="text" class="form-control" name="name" disabled>
                <small class="text-muted"><i>@lang('Usernames cannot be changed.')'</i></small>
              </div>
            </div>

            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }} row">
              <label for="email" class="col-sm-3 col-form-label">@lang('E-mail Address')</label>

              <div class="col">
                <input value="{{$user->email}}" id="email" type="email" class="form-control" name="email">

                @if ($errors->has('email'))
                <span class="help-block text-danger">
                  {{ $errors->first('email') }}
                </span>
                @endif
              </div>
            </div>

            <div class="row">
              <div class="col text-right">
                <button type="submit" class="btn btn-primary">
                  @lang('Save')
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

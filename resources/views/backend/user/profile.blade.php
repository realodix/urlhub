@extends('layouts.backend')

@section('title', Str::title(Auth::user()->name) .' ‹ '. __('Edit Profile'))

@section('content')

@include('messages')

<div class="row">
  <div class="col-xl-4">
    <div class="px-4 sm:px-0">
        <h3>@lang('Profile Information')</h3>

        <p class="mt-1 fw-light">
          @lang("Update your account's profile information and email address.")
        </p>
    </div>
  </div>
  <div class="col-xl-6">
    <form method="post" action="{{route('user.update', $user->getRouteKey())}}">
    @csrf
      <div class="card">
        <div class="card-body">
          <div class="row mt-4 mb-4">
          <div class="col">
            <div class="input-group mb-3{{ $errors->has('name') ? ' has-error' : '' }} row">
              <label for="name" class="col-sm-3 col-form-label">@lang('Username')</label>

              <div class="col">
                <input value="{{$user->name}}" id="name" type="text" class="form-control" name="name" disabled>
                <small class="text-muted"><i>@lang('Usernames cannot be changed.')</i></small>
              </div>
            </div>

            <div class="input-group mb-3{{ $errors->has('email') ? ' has-error' : '' }} row">
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
              <div class="col text-end">
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

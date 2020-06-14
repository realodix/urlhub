@extends('layouts.backend')

@section('title', Str::title(Auth::user()->name) .' ‹ '. __('Change Password'))

@section('content')

@include('messages')

<div class="row">
  <div class="col-xl-6">
    <form method="post" action="{{route('short_url.edit.post', $url->getRouteKey())}}">
    @csrf
      <div class="card">
        <div class="card-body">
          <h4 class="card-title mb-0">
            @lang('My URLs')
            <small class="text-muted">@lang('Edit URL')</small>
          </h4>

          <hr />

          <div class="row mt-4 mb-4">
          <div class="col">
            <div class="form-group{{ $errors->has('short-url') ? ' has-error' : '' }} row">
              <label for="short-url" class="col-sm-3 col-form-label">@lang('Short URL')</label>

              <div class="col">
                <div class="input-group mb-3">
                  <span class="short-url">{{urlRemoveScheme($url->short_url)}}</span>
                </div>
              </div>
            </div>

            <div class="form-group{{ $errors->has('meta-title') ? ' has-error' : '' }} row">
              <label for="meta-title" class="col-sm-3 col-form-label">@lang('Title')</label>

              <div class="col">
                <input id="meta-title" type="text" class="form-control" name="meta_title" placeholder="@lang('Title')" required value="{{$url->meta_title}}">
              </div>
            </div>

            <div class="form-group{{ $errors->has('long-url') ? ' has-error' : '' }} row">
              <label for="long-url" class="col-sm-3 col-form-label">@lang('Long URL')</label>

              <div class="col">
                <input id="long-url" type="text" class="form-control" name="long_url" placeholder="@lang('Enter your long url')" required value="{{$url->long_url}}">
              </div>
            </div>

            <div class="row">
              <div class="col text-right">
                <button type="submit" class="btn btn-secondary">
                  @lang('Save Changes')
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

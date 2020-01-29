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
                      <div class="input-group-prepend">
                          <span class="input-group-text" id="short-addon3">{{$_SERVER['SERVER_NAME'] ?? 'urlhub.test'}}/</span>
                      </div>
                      <input type="text" name="custom_url_key" class="form-control" id="short-url" aria-describedby="short-addon3" required value="{{$url->url_key}}">
                  </div>
                </div>
              </div>

              <div class="form-group{{ $errors->has('long-url') ? ' has-error' : '' }} row">
                <label for="long-url" class="col-sm-3 col-form-label">@lang('Long URL')</label>

                <div class="col">
                  <input id="long-url" type="text" class="form-control" name="long_url" placeholder="Enter your long url" required value="{{$url->long_url}}">
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

@extends('layouts.backend')

@section('title', __('All URLs'))

@section('content')
<div class="all-url">
  <div class="card">
    <div class="card-body">
      <div class="row mb-3">
      <div class="col-sm-6">
        <h4 class="card-title mb-0">
          @lang('All URLs')
        </h4>
      </div><!--col-->
      <div class="col-sm-6">
        <a class="nav-link float-right" href="{{ url('./') }}" target="_blank" title="@lang('Add URL')" data-toggle="tooltip"><i class="fas fa-plus"></i></a>
      </div><!--col-->
      </div><!--row-->

      @include('messages')

      <div class="table-responsive-md">
        <table id="dt-allUrls" class="table table-striped">
          <thead>
            <tr>
              <th scope="col">@lang('Short URL')</th>
              <th scope="col">@lang('Original URL')</th>
              <th scope="col">@lang('Clicks')</th>
              <th scope="col">@lang('Created By')</th>
              <th scope="col">@lang('Date')</th>
              <th scope="col">@lang('Actions')</th>
            </tr>
          </thead>
        </table>
      </div>

    </div>
  </div>
</div>
@endsection

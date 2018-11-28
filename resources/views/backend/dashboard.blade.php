@extends('layouts.backend')

@section('title', __('Dashboard'))

@section('content')
<div class="my-url">

  @include('backend.partials.stat')

  <div class="card">
    <div class="card-body">
      <div class="row mb-3">
      <div class="col-sm-6">
        <h4 class="card-title mb-0">
          @lang('My URLs')
        </h4>
      </div><!--col-->
      <div class="col-sm-6">
        <a class="nav-link float-right" href="{{ url('./') }}" target="_blank" title="@lang('Add URL')" data-toggle="tooltip"><i class="fas fa-plus"></i></a>
      </div><!--col-->
      </div><!--row-->

      @include('messages')

      <table id="dt-myUrls" class="table table-responsive-sm table-striped">
        <thead>
          <tr>
            <th scope="col">@lang('Short URL')</th>
            <th scope="col">@lang('Original URL')</th>
            <th scope="col">@lang('Clicks')</th>
            <th scope="col">@lang('Date')</th>
            <th scope="col">@lang('Actions')</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>
@endsection

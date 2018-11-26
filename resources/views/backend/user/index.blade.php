@extends('layouts.backend')

@section('title', __('All Users'))

@section('content')
<div class="all-url">
  <div class="card">
    <div class="card-body">
      <div class="row">
      <div class="col-sm-6">
        <h4 class="card-title mb-3">
          @lang('All Users')
        </h4>
      </div><!--col-->
      </div><!--row-->

      <table id="dt-Users" class="table table-responsive-sm table-striped">
        <thead>
          <tr>
            <th scope="col">@lang('Username')</th>
            <th scope="col">@lang('E-Mail')</th>
            <th scope="col">@lang('Member Since')</th>
            <th scope="col">@lang('Last Updated')</th>
            <th scope="col">@lang('Actions')</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>
@endsection

@extends('layouts.backend')

@section('title', __('All Users'))

@section('content')
<div class="all-url">
  <div class="bg-white p-4 shadow sm:rounded-md">
    <div class="font-bold text-2xl text-[#73539f] mb-8">
      <span>@lang('All Users')</span>
    </div>

    <div class="overflow-x-auto sm:overflow-x-clip">
      <table id="dt-Users" class="table border-collapse border border-slate-700">
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

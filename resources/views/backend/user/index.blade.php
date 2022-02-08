@extends('layouts.backend')

@section('title', __('All Users'))

@section('content')
<main>
  <div class="bg-white p-4 shadow sm:rounded-md">
    <div class="font-bold text-2xl mb-8">
      <span>@lang('All Users')</span>
    </div>

    @livewire('user-table')
  </div>
</main>
@endsection

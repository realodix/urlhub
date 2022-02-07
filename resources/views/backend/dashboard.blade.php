@extends('layouts.backend')

@section('title', __('Dashboard'))

@section('content')
<main>
  @include('partials.b-stat')

  <div class="bg-white p-4 shadow sm:rounded-md">
    <div class="flex mb-8">
      <div class="w-1/2">
        <span class="font-bold text-2xl text-[#73539f]">
          @lang('My URLs')
        </span>
      </div>
      <div class="w-1/2 text-right">
        <a href="{{ url('./') }}" target="_blank" title="@lang('Add URL')" class="font-bold text-2xl text-[#73539f]">
          <i class="fas fa-plus"></i>
        </a>
      </div>
    </div>

    @include('partials/messages')

    @livewire('my-url-table')
  </div>
</main>
@endsection

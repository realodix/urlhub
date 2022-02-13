@extends('layouts.backend')

@section('title', __('All URLs'))

@section('content')
<main>
  <div class="bg-white p-4 shadow sm:rounded-md">
      <div class="flex mb-8">
        <div class="w-1/2">
          <span class="text-2xl">
            @lang('All URLs')
          </span>
        </div>
        <div class="w-1/2 text-right">
          <a href="{{ url('./') }}" target="_blank" title="@lang('Add URL')" class="font-bold text-2xl text-uh-blue">
            <i class="fas fa-plus"></i>
          </a>
        </div>
      </div>

      @include('partials/messages')

      @livewire('all-ulr-table')
  </div>
</main>
@endsection

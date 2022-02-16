@extends('layouts.backend')

@section('title', __('All URLs'))

@section('content')
<main>
  <div class="bg-white p-4 shadow sm:rounded-md">
      <div class="flex mb-8">
        <div class="w-1/2">
          <span class="text-2xl text-uh-1">
            @lang('All URLs')
          </span>
        </div>
        <div class="w-1/2 text-right">
          <a href="{{ url('./') }}" target="_blank" title="@lang('Add URL')" class="text-sm text-white bg-uh-2b hover:bg-uh-2c active:bg-uh-2b p-2 rounded-md">
            @lang('Add URL')
          </a>
        </div>
      </div>

      @include('partials/messages')

      @livewire('all-ulr-table')
  </div>
</main>
@endsection

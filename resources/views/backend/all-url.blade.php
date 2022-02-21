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
          <a href="{{ url('./') }}" target="_blank" title="@lang('Add URL')" class="inline-flex whitespace-nowrap items-center border font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 px-4 py-2 text-sm leading-5 rounded-md border-transparent shadow-sm text-white bg-uh-indigo-600 hover:bg-uh-indigo-700 focus:ring-uh-indigo-500 ml-4">
            <i class="fa-solid fa-plus mr-2"></i>
            @lang('Add URL')
          </a>
        </div>
      </div>

      @include('partials/messages')

      @livewire('all-ulr-table')
  </div>
</main>
@endsection

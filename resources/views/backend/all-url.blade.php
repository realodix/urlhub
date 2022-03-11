@extends('layouts.backend')

@section('title', __('All URLs'))

@section('content')
<main>
  <div class="bg-white p-4 shadow sm:rounded-md">
      <div class="flex mb-8">
        <div class="w-1/2">
          <span class="text-2xl text-uh-1">
            {{__('All URLs')}}
          </span>
        </div>
        <div class="w-1/2 text-right">
          <a href="{{ url('./') }}" target="_blank" title="{{__('Add URL')}}"
            class="inline-flex ml-4 px-4 py-2 items-center whitespace-nowrap
              focus:outline-none focus:ring-2 focus:ring-offset-2 leading-5 border border-transparent rounded-md shadow-sm
              text-sm font-medium text-white bg-uh-indigo-600 hover:bg-uh-indigo-700 focus:ring-uh-indigo-500"
          >
            @svg('gmdi-add-link', '!h-[1.5em] mr-1')
            {{__('Add URL')}}
          </a>
        </div>
      </div>

      @include('partials/messages')

      @livewire('all-ulr-table')
  </div>
</main>
@endsection

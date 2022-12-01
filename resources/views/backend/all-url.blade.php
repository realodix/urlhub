@extends('layouts.backend')

@section('title', __('All URLs'))

@section('content')
    <main>
        <div class="common-card-style p-4">
            <div class="flex mb-8">
                <div class="w-1/2">
                    <span class="text-2xl text-uh-1">
                        {{__('All URLs')}}
                    </span>
                </div>
                <div class="w-1/2 text-right">
                    <a href="{{ url('./') }}" target="_blank" title="{{__('Add URL')}}" class="btn">
                        @svg('icon-add-link', '!h-[1.5em] mr-1')
                        {{__('Add URL')}}
                    </a>
                </div>
            </div>

            @include('partials/messages')

            @livewire('table.all-ulr-table')
        </div>
    </main>
@endsection

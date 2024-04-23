@extends('layouts.backend')

@section('title', __('Dashboard'))
@section('content')
    <main>
        <div class="mb-4 p-4 bg-uh-bg-1 border-y sm:rounded-lg sm:border border-uh-border-color">
            <div class="flex flex-wrap">
                <div class="w-full sm:w-1/4">
                    <span class="font-semibold sm:text-2xl">@svg('icon-link', 'mr-1.5 text-green-700') {{__('Short links')}}:</span>
                    <span class="font-light sm:text-2xl">{{numberAbbreviate($url->numberOfUrl(auth()->id()))}}</span>
                </div>
                <div class="w-full sm:w-1/4">
                    <span class="font-semibold sm:text-2xl">@svg('icon-bar-chart', 'mr-1.5 text-amber-600') {{__('Clicks')}}:</span>
                    <span class="font-light sm:text-2xl">{{numberAbbreviate($url->numberOfClicksOfEachUser())}}</span>
                </div>
            </div>
        </div>

        <div class="common-card-style">
            <div class="card_header__v2">
                <div class="w-1/2">
                    <span class="text-2xl text-uh-1">{{__('My URLs')}}</span>
                </div>
                <div class="w-1/2 text-right">
                    <a href="{{ url('./') }}" target="_blank" title="{{__('Add URL')}}" class="btn btn-primary">
                        @svg('icon-add-link', '!h-[1.5em] mr-1')
                        {{__('Add URL')}}
                    </a>
                </div>
            </div>

            @include('partials/messages')

            @livewire('table.my-url-table')
        </div>
    </main>
@endsection

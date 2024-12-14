@extends('layouts.backend')

@section('title', __('Dashboard'))
@section('content')
<main>
    <div class="grid gird-cols-1 md:grid-cols-4 gap-7 mb-8">
        <div class="bg-white p-4 sm:rounded-lg
            border-y border-uh-border-color sm:border-none sm:shadow-md"
        >
            <div class="flex flex-row space-x-4 items-center">
                @svg('icon-link', 'mr-1.5 text-emerald-600 text-3xl')
                <div>
                    <p class="text-gray-400 text-sm font-medium uppercase leading-4">Total Links</p>
                    <p class="text-2xl font-bold text-gray-900 inline-flex items-center space-x-2">
                        {{ n_abb($url->currentUserUrlCount(auth()->id())) }}
                    </p>
                </div>
            </div>
        </div>
        <div class="bg-white p-4 sm:rounded-lg
            border-y border-uh-border-color sm:border-none sm:shadow-md"
        >
            <div class="flex flex-row space-x-4 items-center">
                @svg('icon-chart-line-alt', 'mr-1.5 text-amber-600 text-3xl')
                <div>
                    <p class="text-gray-400 text-sm font-medium uppercase leading-4">Total Clicks</p>
                    <p class="text-2xl font-bold text-gray-900 inline-flex items-center space-x-2">
                        {{ $urlVisitCount }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="common-card-style">
        <div class="card_header__v2">
            <div class="w-1/2">
                <span class="text-2xl text-black">{{ __('My URLs') }}</span>
            </div>
            <div class="w-1/2 text-right">
                <a href="{{ url('./') }}" target="_blank" title="{{ __('Add URL') }}" class="btn btn-primary">
                    @svg('icon-add-link', '!h-[1.5em] mr-1')
                    {{ __('Add URL') }}
                </a>
            </div>
        </div>

        @include('partials/messages')

        @livewire('table.my-url-table')
    </div>
</main>
@endsection

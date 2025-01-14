@extends('layouts.backend')

@section('title', __('Dashboard'))
@section('content')
<main>
    <div class="grid gird-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="--card-style p-4">
            <div class="flex flex-row space-x-4 items-center">
                @svg('icon-link', 'mr-1.5 text-emerald-600 text-3xl')
                <div>
                    <p class="text-slate-600 text-sm font-medium leading-4">Total Links</p>
                    <p class="text-2xl font-bold text-slate-900 inline-flex items-center space-x-2">
                        {{ n_abb($url->authUserUrlCount(auth()->id())) }}
                    </p>
                </div>
            </div>
        </div>
        <div class="--card-style p-4">
            <div class="flex flex-row space-x-4 items-center">
                @svg('icon-chart-line-alt', 'mr-1.5 text-amber-600 text-3xl')
                <div>
                    <p class="text-slate-600 text-sm font-medium leading-4">Total Clicks</p>
                    <p class="text-2xl font-bold text-slate-900 inline-flex items-center space-x-2">
                        {{ $urlVisitCount }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="content-header">
            <p class="text-2xl">{{ __('My URLs') }}</p>
            <div class="flex justify-end">
                <a href="{{ url('./') }}" target="_blank" title="{{ __('Add URL') }}" class="btn btn-secondary">
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

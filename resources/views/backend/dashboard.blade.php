@extends('layouts.backend')

@section('title', __('Dashboard'))
@section('content')
<div class="container-alt max-w-340">
    <div class="w-full md:max-w-md">
        @include('partials/messages')
    </div>

    <div class="grid gird-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-8">
        <div class="card card-fluid shadow-xs p-4">
            <div class="flex flex-row space-x-4 items-center">
                @svg('icon-link', 'mr-1.5 text-emerald-600 text-3xl')
                <div>
                    <p class="text-slate-600 dark:text-dark-400 text-sm font-medium leading-4">Total Links</p>
                    <p class="text-2xl font-bold text-slate-700 dark:text-dark-100 inline-flex items-center space-x-2">
                        {{ n_abb($url->authUserLinks(auth()->id())) }}
                    </p>
                </div>
            </div>
        </div>
        <div class="card card-fluid shadow-xs p-4">
            <div class="flex flex-row space-x-4 items-center">
                @svg('icon-chart-line-alt', 'mr-1.5 text-amber-600 text-3xl')
                <div>
                    <p class="text-slate-600 dark:text-dark-400 text-sm font-medium leading-4">Total Clicks</p>
                    <p class="text-2xl font-bold text-slate-700 dark:text-dark-100 inline-flex items-center space-x-2">
                        {{ $urlVisitCount }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="content-container card card-fluid">
        <div class="content-header">
            <p class="text-2xl">{{ __('My URLs') }}</p>
            <div class="flex justify-end">
                <a href="{{ url('./') }}" target="_blank" title="{{ __('Add URL') }}" class="btn btn-primary">
                    @svg('icon-add-link', '!h-[1.5em] mr-1')
                    <p class="hidden sm:inline">{{ __('Add URL') }}</p>
                </a>
            </div>
        </div>

        @livewire('table.my-url-table')
    </div>
</div>
@endsection

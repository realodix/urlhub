@extends('layouts.backend')

@section('title', 'Dashboard')
@section('content')
<div class="container-alt max-w-340">
    <div class="w-full md:max-w-md">
        @include('partials.messages')
    </div>

    <div class="grid gird-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-8">
        <div class="card card-master shadow-xs p-4">
            <div class="flex justify-between items-end text-slate-600 dark:text-dark-400">
                <span class="text-sm font-medium">Links</span>
                @svg('icon-link', 'mr-1.5 size-4')
            </div>
            <p class="text-2xl font-bold text-slate-700 dark:text-dark-200 inline-flex items-center space-x-2">
                {{ n_abb(auth()->user()->urls()->count()) }}
            </p>
        </div>
        <div class="card card-master shadow-xs p-4">
            <div class="flex justify-between items-end text-slate-600 dark:text-dark-400">
                <span class="text-sm font-medium">Visits</span>
                @svg('icon-chart-line-alt', 'mr-1.5 size-4')
            </div>
            <p class="text-2xl font-bold text-slate-700 dark:text-dark-200 inline-flex items-center space-x-2">
                {{ n_abb(auth()->user()->visits()->count()) }}
            </p>
        </div>
        <div class="card card-master shadow-xs p-4">
            <div class="flex justify-between items-end text-slate-600 dark:text-dark-400">
                <span class="text-sm font-medium">Visitors</span>
                @svg('icon-people', 'mr-1.5 size-4')
            </div>
            <p class="text-2xl font-bold text-slate-700 dark:text-dark-200 inline-flex items-center space-x-2">
                {{ n_abb(auth()->user()->visits()->distinct('visits.user_uid')->count()) }}
            </p>
        </div>
    </div>

    <div class="content-container card card-master">
        <div class="content-header">
            <p class="text-2xl">My URLs</p>
            <div class="flex justify-end">
                <a href="{{ route('user.overview', auth()->user()) }}" title="Overview" class="btn btn-secondary">
                    @svg('icon-overview', 'h-[1.5em]! mr-1')
                    <p class="hidden sm:inline">Overview</p>
                </a>
            </div>
        </div>

        @livewire('table.UrlTableByUser', ['user_id' => auth()->id()])
    </div>
</div>
@endsection

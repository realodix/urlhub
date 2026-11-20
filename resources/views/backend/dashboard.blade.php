@extends('layouts.backend')

@section('title', 'Dashboard')
@section('content')
<div class="container-alt max-w-340">
    <div class="w-full md:max-w-md">
        @include('partials.messages')
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
        <!-- Card 1: Links -->
        <div class="group relative overflow-hidden rounded-2xl border border-gray-200/80 bg-white p-5 shadow-xs transition-all duration-200 hover:-translate-y-0.5 hover:border-gray-300 hover:shadow-md dark:border-neutral-800 dark:bg-neutral-900 dark:hover:border-neutral-700">
            <!-- Subtle decorative background glow -->
            <div class="pointer-events-none absolute -right-6 -top-6 h-24 w-24 rounded-full bg-emerald-500/5 transition-transform duration-300 group-hover:scale-125 dark:bg-emerald-400/10"></div>

            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-neutral-400">
                        Total Links
                    </p>
                    <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900 dark:text-white">
                        {{ n_abb(auth()->user()->urls()->count()) }}
                    </p>
                </div>
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-emerald-200/60 bg-emerald-50 text-emerald-600 dark:border-emerald-800/40 dark:bg-emerald-950/60 dark:text-emerald-400">
                    @svg('icon-link', 'size-5')
                </div>
            </div>

            <div class="mt-4 flex items-center gap-1.5 border-t border-gray-100 pt-3 text-xs text-gray-500 dark:border-neutral-800 dark:text-neutral-400">
                <span class="inline-block h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                <span>Total shortened URLs</span>
            </div>
        </div>

        <!-- Card 2: Visits -->
        <div class="group relative overflow-hidden rounded-2xl border border-gray-200/80 bg-white p-5 shadow-xs transition-all duration-200 hover:-translate-y-0.5 hover:border-gray-300 hover:shadow-md dark:border-neutral-800 dark:bg-neutral-900 dark:hover:border-neutral-700">
            <!-- Subtle decorative background glow -->
            <div class="pointer-events-none absolute -right-6 -top-6 h-24 w-24 rounded-full bg-amber-500/5 transition-transform duration-300 group-hover:scale-125 dark:bg-amber-400/10"></div>

            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-neutral-400">
                        Total Visits
                    </p>
                    <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900 dark:text-white">
                        {{ n_abb(auth()->user()->visits()->count()) }}
                    </p>
                </div>
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-amber-200/60 bg-amber-50 text-amber-600 dark:border-amber-800/40 dark:bg-amber-950/60 dark:text-amber-400">
                    @svg('icon-chart-line-alt', 'size-5')
                </div>
            </div>

            <div class="mt-4 flex items-center gap-1.5 border-t border-gray-100 pt-3 text-xs text-gray-500 dark:border-neutral-800 dark:text-neutral-400">
                <span class="inline-block h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                <span>All-time link clicks</span>
            </div>
        </div>

        <!-- Card 3: Visitors -->
        <div class="group relative overflow-hidden rounded-2xl border border-gray-200/80 bg-white p-5 shadow-xs transition-all duration-200 hover:-translate-y-0.5 hover:border-gray-300 hover:shadow-md dark:border-neutral-800 dark:bg-neutral-900 dark:hover:border-neutral-700">
            <!-- Subtle decorative background glow -->
            <div class="pointer-events-none absolute -right-6 -top-6 h-24 w-24 rounded-full bg-blue-500/5 transition-transform duration-300 group-hover:scale-125 dark:bg-blue-400/10"></div>

            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-neutral-400">
                        Unique Visitors
                    </p>
                    <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900 dark:text-white">
                        {{ n_abb(auth()->user()->visits()->distinct('visits.user_uid')->count()) }}
                    </p>
                </div>
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-blue-200/60 bg-blue-50 text-blue-600 dark:border-blue-800/40 dark:bg-blue-950/60 dark:text-blue-400">
                    @svg('icon-people', 'size-5')
                </div>
            </div>

            <div class="mt-4 flex items-center gap-1.5 border-t border-gray-100 pt-3 text-xs text-gray-500 dark:border-neutral-800 dark:text-neutral-400">
                <span class="inline-block h-1.5 w-1.5 rounded-full bg-blue-500"></span>
                <span>Distinct audience reach</span>
            </div>
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

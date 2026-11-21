@extends('layouts.backend')

@section('title', 'Overview ‹ '.str()->title($user->name))
@section('content')
<div class="container-alt max-w-340">
    <div class="grid gird-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-8">
        <!-- Card 1: Links -->
        <div class="group relative overflow-hidden rounded-2xl border border-gray-200/80 bg-white p-5 shadow-xs duration-200 hover:border-gray-300 dark:border-neutral-800 dark:bg-neutral-900 dark:hover:border-neutral-700">
            <!-- Subtle decorative background glow -->
            <div class="pointer-events-none absolute -right-6 -top-6 h-24 w-24 rounded-full bg-emerald-500/5 transition-transform duration-300 group-hover:scale-125 dark:bg-emerald-400/10"></div>

            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-neutral-400">
                        Links
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
                <span>Your shortened URLs</span>
            </div>
        </div>

        <!-- Card 2: Visits -->
        <div class="group relative overflow-hidden rounded-2xl border border-gray-200/80 bg-white p-5 shadow-xs duration-200 hover:border-gray-300 dark:border-neutral-800 dark:bg-neutral-900 dark:hover:border-neutral-700">
            <!-- Subtle decorative background glow -->
            <div class="pointer-events-none absolute -right-6 -top-6 h-24 w-24 rounded-full bg-amber-500/5 transition-transform duration-300 group-hover:scale-125 dark:bg-amber-400/10"></div>

            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-neutral-400">
                        Visits
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
                <span>Total clicks on your links</span>
            </div>
        </div>

        <!-- Card 3: Visitors -->
        <div class="group relative overflow-hidden rounded-2xl border border-gray-200/80 bg-white p-5 shadow-xs duration-200 hover:border-gray-300 dark:border-neutral-800 dark:bg-neutral-900 dark:hover:border-neutral-700">
            <!-- Subtle decorative background glow -->
            <div class="pointer-events-none absolute -right-6 -top-6 h-24 w-24 rounded-full bg-blue-500/5 transition-transform duration-300 group-hover:scale-125 dark:bg-blue-400/10"></div>

            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-neutral-400">
                        Visitors
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
                <span>Unique visitors reached</span>
            </div>
        </div>
    </div>

    <x-tabs :tabs="['day' => 'Day', 'week' => 'Week', 'month' => 'Month']" contentClass="card-master p-1">
        <x-slot:day>
            @livewire(\App\Livewire\Chart\LinkVisitChart::class, ['model' => $user])
        </x-slot>
        <x-slot:week>
            @livewire(\App\Livewire\Chart\LinkVisitPerWeekChart::class, ['model' => $user])
        </x-slot>
        <x-slot:month>
            @livewire(\App\Livewire\Chart\LinkVisitPerMonthChart::class, ['model' => $user])
        </x-slot>
    </x-tabs>

    <br>

    @php
        $tVisits = $user->visits()->count();
        $topUrls = $linkService->getTopUrlsByVisits($user);
        $topReferrers = $visitService->topReferrers($user);
        $topBrowsers = $visitService->topBrowsers($user);
        $topOS = $visitService->topOperatingSystems($user);
    @endphp

    {{-- First Row: Visited URLs & Referrers --}}
    <div class="grid grid-cols-1 gap-5 lg:grid-cols-[3fr_2fr]">
        {{-- Visited URLs --}}
        <div class="rounded-xl border border-gray-200 bg-white dark:border-dark-800 dark:bg-dark-900">
            <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4 dark:border-dark-800">
                <div>
                    <h2 class="text-sm font-semibold text-gray-900 dark:text-white">
                        Visited URLs
                    </h2>
                    <p class="mt-1 text-xs text-gray-500 dark:text-dark-400">
                        Most visited short links
                    </p>
                </div>

                <span class="text-xs text-gray-400 dark:text-dark-500">
                    Top links
                </span>
            </div>

            <div class="p-2">
                @forelse ($topUrls as $index => $url)
                    @php
                        $percentage = $tVisits > 0
                            ? round(($url->visits_count / $tVisits) * 100, 1)
                            : 0;
                    @endphp

                    <div class="group rounded-lg px-3 py-3 transition-colors hover:bg-gray-50 dark:hover:bg-dark-800/50">
                        <div class="flex items-center justify-between gap-4">
                            <div class="flex min-w-0 items-center gap-3">
                                <span class="w-5 shrink-0 text-xs tabular-nums text-gray-400 dark:text-dark-500">
                                    {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                </span>

                                <div class="min-w-0">
                                    <div class="flex min-w-0 items-center gap-2">
                                        <a
                                            href="{{ $url->short_url }}"
                                            target="_blank"
                                            class="truncate text-sm font-medium text-gray-900 hover:text-emerald-600 dark:text-white dark:hover:text-emerald-400"
                                        >
                                            {{ $url->keyword }}
                                        </a>

                                        <a
                                            href="{{ route('link.edit', $url) }}"
                                            class="shrink-0 text-xs text-gray-400 opacity-0 transition-opacity group-hover:opacity-100 hover:text-gray-700 dark:hover:text-dark-200"
                                        >
                                            Edit
                                        </a>
                                    </div>

                                    <p class="mt-0.5 truncate text-xs text-gray-500 dark:text-dark-400" title="{{ $url->destination }}">
                                        {{ Str::limit($url->destination, 55) }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex shrink-0 items-center gap-3 text-right tabular-nums">
                                <span class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ number_format($url->visits_count) }}
                                </span>

                                <span class="w-10 text-xs text-gray-400 dark:text-dark-500">
                                    {{ $percentage }}%
                                </span>
                            </div>
                        </div>

                        <div class="mt-2 ml-8 h-1 overflow-hidden rounded-full bg-gray-100 dark:bg-dark-800">
                            <div
                                class="h-full rounded-full bg-emerald-500/80 transition-all duration-300"
                                style="width: {{ min($percentage, 100) }}%"
                            ></div>
                        </div>
                    </div>
                @empty
                    <div class="flex h-40 flex-col items-center justify-center text-center">
                        <p class="text-sm text-gray-500 dark:text-dark-400">
                            No visit data yet
                        </p>
                        <p class="mt-1 text-xs text-gray-400 dark:text-dark-500">
                            Share your links to start tracking clicks.
                        </p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Referrers --}}
        <div class="rounded-xl border border-gray-200 bg-white dark:border-dark-800 dark:bg-dark-900">
            <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4 dark:border-dark-800">
                <div>
                    <h2 class="text-sm font-semibold text-gray-900 dark:text-white">
                        Referrers
                    </h2>
                    <p class="mt-1 text-xs text-gray-500 dark:text-dark-400">
                        Where your visitors come from
                    </p>
                </div>

                <span class="text-xs text-gray-400 dark:text-dark-500">
                    Sources
                </span>
            </div>

            <div class="p-2">
                @forelse ($topReferrers as $index => $referrerData)
                    @php
                        $percentage = $tVisits > 0
                            ? round(($referrerData->total / $tVisits) * 100, 1)
                            : 0;
                    @endphp

                    <div class="rounded-lg px-3 py-3 transition-colors hover:bg-gray-50 dark:hover:bg-dark-800/50">
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex min-w-0 items-center gap-3">
                                <span class="w-5 shrink-0 text-xs tabular-nums text-gray-400 dark:text-dark-500">
                                    {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                </span>

                                <div class="min-w-0">
                                    @if ($referrerData->referer)
                                        <a
                                            href="{{ $referrerData->referer }}"
                                            target="_blank"
                                            title="{{ $referrerData->referer }}"
                                            class="block truncate text-sm font-medium text-gray-800 hover:text-emerald-600 dark:text-dark-200 dark:hover:text-emerald-400"
                                        >
                                            {{ Str::limit($referrerData->referer, 42) }}
                                        </a>
                                    @else
                                        <span class="text-sm font-medium text-gray-500 dark:text-dark-400">
                                            Direct / Unknown
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="flex shrink-0 items-center gap-3 text-right tabular-nums">
                                <span class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ number_format($referrerData->total) }}
                                </span>

                                <span class="w-10 text-xs text-gray-400 dark:text-dark-500">
                                    {{ $percentage }}%
                                </span>
                            </div>
                        </div>

                        <div class="mt-2 ml-8 h-1 overflow-hidden rounded-full bg-gray-100 dark:bg-dark-800">
                            <div
                                class="h-full rounded-full bg-amber-400/80 transition-all duration-300"
                                style="width: {{ min($percentage, 100) }}%"
                            ></div>
                        </div>
                    </div>
                @empty
                    <div class="flex h-40 flex-col items-center justify-center text-center">
                        <p class="text-sm text-gray-500 dark:text-dark-400">
                            No referrer data yet
                        </p>
                        <p class="mt-1 text-xs text-gray-400 dark:text-dark-500">
                            Traffic sources will appear here once visited.
                        </p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Second Row: Browsers & Operating Systems --}}
    <div class="mt-5 grid grid-cols-1 gap-5 md:grid-cols-2">
        {{-- Browsers --}}
        <div class="rounded-xl border border-gray-200 bg-white dark:border-dark-800 dark:bg-dark-900">
            <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4 dark:border-dark-800">
                <div>
                    <h2 class="text-sm font-semibold text-gray-900 dark:text-white">
                        Browsers
                    </h2>
                    <p class="mt-1 text-xs text-gray-500 dark:text-dark-400">
                        Browsers used by your visitors
                    </p>
                </div>

                <span class="text-xs text-gray-400 dark:text-dark-500">
                    Clients
                </span>
            </div>

            <div class="p-2">
                @forelse ($topBrowsers as $index => $browserData)
                    @php
                        $percentage = $tVisits > 0
                            ? round(($browserData->total / $tVisits) * 100, 1)
                            : 0;
                    @endphp

                    <div class="rounded-lg px-3 py-3 transition-colors hover:bg-gray-50 dark:hover:bg-dark-800/50">
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex min-w-0 items-center gap-3">
                                <span class="w-5 shrink-0 text-xs tabular-nums text-gray-400 dark:text-dark-500">
                                    {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                </span>

                                <span class="truncate text-sm font-medium text-gray-800 dark:text-dark-200">
                                    {{ $browserData->browser ?: 'Unknown' }}
                                </span>
                            </div>

                            <div class="flex shrink-0 items-center gap-3 text-right tabular-nums">
                                <span class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ number_format($browserData->total) }}
                                </span>

                                <span class="w-10 text-xs text-gray-400 dark:text-dark-500">
                                    {{ $percentage }}%
                                </span>
                            </div>
                        </div>

                        <div class="mt-2 ml-8 h-1 overflow-hidden rounded-full bg-gray-100 dark:bg-dark-800">
                            <div
                                class="h-full rounded-full bg-indigo-400/80 transition-all duration-300"
                                style="width: {{ min($percentage, 100) }}%"
                            ></div>
                        </div>
                    </div>
                @empty
                    <div class="flex h-40 flex-col items-center justify-center text-center">
                        <p class="text-sm text-gray-500 dark:text-dark-400">
                            No browser data yet
                        </p>
                        <p class="mt-1 text-xs text-gray-400 dark:text-dark-500">
                            Visitor browser stats will appear here.
                        </p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Operating Systems --}}
        <div class="rounded-xl border border-gray-200 bg-white dark:border-dark-800 dark:bg-dark-900">
            <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4 dark:border-dark-800">
                <div>
                    <h2 class="text-sm font-semibold text-gray-900 dark:text-white">
                        Operating Systems
                    </h2>
                    <p class="mt-1 text-xs text-gray-500 dark:text-dark-400">
                        Platforms used by your visitors
                    </p>
                </div>

                <span class="text-xs text-gray-400 dark:text-dark-500">
                    Platforms
                </span>
            </div>

            <div class="p-2">
                @forelse ($topOS as $index => $osData)
                    @php
                        $percentage = $tVisits > 0
                            ? round(($osData->total / $tVisits) * 100, 1)
                            : 0;
                    @endphp

                    <div class="rounded-lg px-3 py-3 transition-colors hover:bg-gray-50 dark:hover:bg-dark-800/50">
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex min-w-0 items-center gap-3">
                                <span class="w-5 shrink-0 text-xs tabular-nums text-gray-400 dark:text-dark-500">
                                    {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                </span>

                                <span class="truncate text-sm font-medium text-gray-800 dark:text-dark-200">
                                    {{ $osData->os ?: 'Unknown' }}
                                </span>
                            </div>

                            <div class="flex shrink-0 items-center gap-3 text-right tabular-nums">
                                <span class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ number_format($osData->total) }}
                                </span>

                                <span class="w-10 text-xs text-gray-400 dark:text-dark-500">
                                    {{ $percentage }}%
                                </span>
                            </div>
                        </div>

                        <div class="mt-2 ml-8 h-1 overflow-hidden rounded-full bg-gray-100 dark:bg-dark-800">
                            <div
                                class="h-full rounded-full bg-blue-400/80 transition-all duration-300"
                                style="width: {{ min($percentage, 100) }}%"
                            ></div>
                        </div>
                    </div>
                @empty
                    <div class="flex h-40 flex-col items-center justify-center text-center">
                        <p class="text-sm text-gray-500 dark:text-dark-400">
                            No OS data yet
                        </p>
                        <p class="mt-1 text-xs text-gray-400 dark:text-dark-500">
                            Visitor platform stats will appear here.
                        </p>
                    </div>
                @endforelse
            </div>
        </div>
    </div> {{-- End Second Row Grid Container --}}
</div>
@endsection

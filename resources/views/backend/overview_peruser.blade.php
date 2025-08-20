@extends('layouts.backend')

@section('title', 'Overview ‹ '.str()->title($user->name))
@section('content')
<div class="container-alt max-w-340">
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

    {{-- Start Grid Container for Statistics --}}
    <div class="grid grid-cols-1 md:grid-cols-[3fr_2fr] gap-4">
        @php
            $tVisits = $user->visits()->count();
        @endphp
        {{-- Visited URLs Card (Col 1, Row 1 - Wider) --}}
        <div class="card card-master overflow-hidden p-4 md:p-8">
            <div class="mb-4">
                <h2 class="text-2xl dark:text-dark-200 tracking-tight">Visited URLs</h2>
                <p class="mt-2 text-md text-gray-600 dark:text-dark-400">A list of your short URLs with the highest number of visits.</p>
            </div>
            <div>
                @php
                    $topUrls = $linkService->getTopUrlsByVisits($user);
                @endphp
                @forelse ($topUrls as $index => $url)
                    <div class="flex items-center py-2">
                        <div class="flex-1">
                            <div class="flex justify-between items-center text-sm md:text-base mb-1">
                                <div>
                                    <span class="dark:text-dark-300 dark:font-semibold">#{{ $index + 1 }} -</span>
                                    <span class="text-gray-600 mt-1">
                                        <a href="{{ $url->short_url }}" target="_blank" class="text-blue-600 dark:text-emerald-400 hover:underline font-medium">
                                            {{ $url->keyword }}
                                        </a>
                                        <a href="{{ route('link.edit', $url) }}" class="dark:text-dark-400 hover:underline">
                                            {{ Str::limit($url->destination, 50) }}
                                        </a>
                                    </span>
                                </div>

                                @php
                                    $percentage = round(($url->visits_count/$tVisits) * 100, 2);
                                @endphp
                                <span class="text-sm font-medium text-blue-600 dark:text-emerald-400">
                                    {{ $url->visits_count }} ({{ $percentage }}%)
                                </span>
                            </div>
                            <x-progress-bar percentage="{{ $percentage }}" />
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center">Data is not yet available.</p>
                @endforelse
            </div>
        </div>

        {{-- Referrers Card (Col 2, Row 1 - Smaller) --}}
        <div class="card card-master overflow-hidden p-4 md:p-8">
            <div class="mb-4">
                <h2 class="text-2xl dark:text-dark-200 tracking-tight">Referrers</h2>
                <p class="mt-2 text-md text-gray-600 dark:text-dark-400">The most common sources of traffic to your short URLs.</p>
            </div>
            <div>
                @php
                    $topReferrers = $visitService->topReferrers($user);
                @endphp
                @forelse ($topReferrers as $index => $referrerData)
                    <div class="flex items-center py-2">
                        <div class="flex-1">
                            <div class="flex justify-between items-center text-sm md:text-base mb-1">
                                <div>
                                    <span class="dark:text-dark-300 dark:font-semibold">#{{ $index + 1 }} -</span>
                                    <span class="text-gray-600 mt-1">
                                        @if ($referrerData->referer)
                                            <a href="{{ $referrerData->referer }}" target="_blank" class="text-blue-600 dark:text-emerald-400 hover:underline">
                                                {{ Str::limit($referrerData->referer, 50) }}
                                            </a>
                                        @else
                                            <span class="dark:text-dark-400">
                                                Direct / Unknown
                                            </span>
                                        @endif
                                    </span>
                                </div>

                                @php
                                    $percentage = round(($referrerData->total/$tVisits) * 100, 2);
                                @endphp
                                <div class="text-sm font-medium text-blue-600 dark:text-emerald-400">
                                    {{ number_format($referrerData->total) }} ({{ $percentage }}%)
                                </div>
                            </div>
                            <x-progress-bar percentage="{{ $percentage }}" />
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center">Data is not yet available.</p>
                @endforelse
            </div>
        </div>
    </div> {{-- End First Row Grid Container --}}

    {{-- Start Second Row Grid Container (Browsers & OS) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
        {{-- Browsers Card (Col 1, Row 2 - Same Width) --}}
        <div class="card card-master overflow-hidden p-4 md:p-8">
            <div class="mb-4">
                <h2 class="text-2xl dark:text-dark-200 tracking-tight">Browsers</h2>
                <p class="mt-2 text-md text-gray-600 dark:text-dark-400">The most common browsers used to visit your short URLs.</p>
            </div>
            <div>
                @php
                    $topBrowsers = $visitService->topBrowsers($user);
                @endphp
                @forelse ($topBrowsers as $index => $browserData)
                    <div class="flex items-center py-2">
                        <div class="flex-1">
                            <div class="flex justify-between items-center text-sm md:text-base mb-1">
                                <div>
                                    <span class="dark:text-dark-300 dark:font-semibold">#{{ $index + 1 }} -</span>
                                    <span class="text-gray-600 dark:text-dark-400 mt-1">
                                        @if($browserData->browser) {{ $browserData->browser }} @else Unknown @endif
                                    </span>
                                </div>

                                @php
                                    $percentage = round(($browserData->total/$tVisits) * 100, 2);
                                @endphp
                                <div class="text-sm font-medium text-blue-600 dark:text-emerald-400">
                                    {{ number_format($browserData->total) }} ({{ $percentage }}%)
                                </div>
                            </div>
                            <x-progress-bar percentage="{{ $percentage }}" />
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center">Data is not yet available.</p>
                @endforelse
            </div>
        </div>

        {{-- Operating Systems Card (Col 2, Row 2 - Same Width) --}}
        <div class="card card-master overflow-hidden p-4 md:p-8">
            <div class="mb-4">
                <h2 class="text-2xl dark:text-dark-200 tracking-tight">Operating Systems</h2>
                <p class="mt-2 text-md text-gray-600 dark:text-dark-400">The most common operating systems used to visit your short URLs.</p>
            </div>
            <div>
                @php
                    $topOS = $visitService->topOperatingSystems($user);
                @endphp
                @forelse ($topOS as $index => $osData)
                    <div class="flex items-center py-2">
                        <div class="flex-1">
                            <div class="flex justify-between items-center text-sm md:text-base mb-1">
                                <div>
                                    <span class="dark:text-dark-300 dark:font-semibold">#{{ $index + 1 }} -</span>
                                    <span class="text-gray-600 dark:text-dark-400 mt-1">
                                        @if($osData->os) {{ $osData->os }} @else Unknown @endif
                                    </span>
                                </div>

                                @php
                                    $percentage = round(($osData->total/$tVisits) * 100, 2);
                                @endphp
                                <div class="text-sm font-medium text-blue-600 dark:text-emerald-400">
                                    {{ number_format($osData->total) }} ({{ $percentage }}%)
                                </div>
                            </div>
                            <x-progress-bar percentage="{{ $percentage }}" />
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center">Data is not yet available.</p>
                @endforelse
            </div>
        </div>
    </div> {{-- End Second Row Grid Container --}}
</div>
@endsection

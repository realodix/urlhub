@extends('layouts.backend')

@section('title', 'Overview ‹ '.str()->title($user->name))
@section('content')
<div class="container-alt max-w-340">
    <div x-data="{activeTab: 'tabDay'}">
        <div>
            <ul class="flex space-x-4 -mb-px ml-2">
                @php
                    $activeTabClasses = 'bg-white dark:bg-dark-800 text-gray-800 dark:text-emerald-500 border-l border-r border-t border-border-300 dark:border-dark-700';
                    $inactiveTabClasses = 'text-dark-500 dark:hover:text-emerald-700';
                @endphp
                <li class="mr-2">
                    <button
                        @click="activeTab = 'tabDay'"
                        :class="{ '{{ $activeTabClasses }}': activeTab === 'tabDay', '{{ $inactiveTabClasses }}': activeTab !== 'tabDay' }"
                        class="px-4 py-2 rounded-t-lg font-medium focus:outline-none cursor-pointer"
                        type="button"
                    >
                        Day
                    </button>
                </li>
                <li class="mr-2">
                    <button
                        @click="activeTab = 'tabWeek'"
                        :class="{ '{{ $activeTabClasses }}': activeTab === 'tabWeek', '{{ $inactiveTabClasses }}': activeTab !== 'tabWeek' }"
                        class="px-4 py-2 rounded-t-lg font-medium focus:outline-none cursor-pointer"
                        type="button"
                    >
                        Week
                    </button>
                </li>
                <li class="mr-2">
                    <button
                        @click="activeTab = 'tabMonth'"
                        :class="{ '{{ $activeTabClasses }}': activeTab === 'tabMonth', '{{ $inactiveTabClasses }}': activeTab !== 'tabMonth' }"
                        class="px-4 py-2 rounded-t-lg font-medium focus:outline-none cursor-pointer"
                        type="button"
                    >
                        Month
                    </button>
                </li>
            </ul>
        </div>

        <!-- Horizontal Tab Content -->
        <div class="card card-master p-1">
            <div>
                <div x-show="activeTab === 'tabDay'">
                    @livewire(\App\Livewire\Chart\LinkVisitChart::class, ['model' => $user])
                </div>
                <div x-show="activeTab === 'tabWeek'">
                    @livewire(\App\Livewire\Chart\LinkVisitPerWeekChart::class, ['model' => $user])
                </div>
                <div x-show="activeTab === 'tabMonth'">
                    @livewire(\App\Livewire\Chart\LinkVisitPerMonthChart::class, ['model' => $user])
                </div>
            </div>
        </div>
    </div>

    <br>

    {{-- Start Grid Container for Statistics --}}
    <div class="grid grid-cols-1 md:grid-cols-[3fr_2fr] gap-4">
        {{-- Visited URLs Card (Col 1, Row 1 - Wider) --}}
        <div class="card card-master overflow-hidden p-4 md:p-8">
            <div class="mb-8">
                <h2 class="text-2xl dark:text-dark-200 tracking-tight">Visited URLs</h2>
                <p class="mt-2 text-md text-gray-600 dark:text-dark-400">A list of your short URLs with the highest number of visits.</p>
            </div>
            <div>
                @php
                    $topUrls = $linkService->getTopUrlsByVisits($user);
                @endphp
                @forelse ($topUrls as $index => $url)
                    <div class="flex items-center border-b border-border-200 dark:border-dark-700 last:border-b-0 py-2">
                        <div class="flex-1">
                            <div class="flex justify-between items-center text-sm md:text-base mb-1">
                                <div>
                                    <span class="dark:text-dark-300 dark:font-semibold">#{{ $index + 1 }} -</span>
                                    <span class="text-gray-600 mt-1">
                                        <a href="{{ $url->short_url }}" target="_blank" class="text-blue-600 dark:text-emerald-400 hover:underline font-medium">
                                            {{ $url->keyword }}
                                        </a>
                                        <a href="{{ route('link.edit', $url) }}" class="dark:text-dark-400 hover:underline">
                                            {{ Str::limit($url->destination, 70) }}
                                        </a>
                                    </span>
                                </div>
                                <span class="text-sm font-medium text-blue-600 dark:text-emerald-400">
                                    {{ $url->visits_count }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center">Data is not yet available.</p>
                @endforelse
            </div>
        </div>

        {{-- Referrers Card (Col 2, Row 1 - Smaller) --}}
        <div class="card card-master overflow-hidden p-4 md:p-8">
            <div class="mb-8">
                <h2 class="text-2xl dark:text-dark-200 tracking-tight">Referrers</h2>
                <p class="mt-2 text-md text-gray-600 dark:text-dark-400">The most common sources of traffic to your short URLs.</p>
            </div>
            <div>
                @php
                    $topReferrers = $visitService->topReferrers($user);
                @endphp
                @forelse ($topReferrers as $index => $referrerData)
                    <div class="flex items-center border-b border-border-200 dark:border-dark-700 last:border-b-0 py-2">
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
                                <div class="text-sm font-medium text-blue-600 dark:text-emerald-400">
                                    {{ number_format($referrerData->total) }}
                                </div>
                            </div>
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
            <div class="mb-8">
                <h2 class="text-2xl dark:text-dark-200 tracking-tight">Browsers</h2>
                <p class="mt-2 text-md text-gray-600 dark:text-dark-400">The most common browsers used to visit your short URLs.</p>
            </div>
            <div>
                @php
                    $topBrowsers = $visitService->topBrowsers($user);
                @endphp
                @forelse ($topBrowsers as $index => $browserData)
                    <div class="flex items-center border-b border-border-200 dark:border-dark-700 last:border-b-0 py-2">
                        <div class="flex-1">
                            <div class="flex justify-between items-center text-sm md:text-base mb-1">
                                <div>
                                    <span class="dark:text-dark-300 dark:font-semibold">#{{ $index + 1 }} -</span>
                                    <span class="text-gray-600 dark:text-dark-400 mt-1">
                                        @if($browserData->browser) {{ $browserData->browser }} @else Unknown @endif
                                    </span>
                                </div>
                                <div class="text-sm font-medium text-blue-600 dark:text-emerald-400">
                                    {{ number_format($browserData->total) }}
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center">Data is not yet available.</p>
                @endforelse
            </div>
        </div>

        {{-- Operating Systems Card (Col 2, Row 2 - Same Width) --}}
        <div class="card card-master overflow-hidden p-4 md:p-8">
            <div class="mb-8">
                <h2 class="text-2xl dark:text-dark-200 tracking-tight">Operating Systems</h2>
                <p class="mt-2 text-md text-gray-600 dark:text-dark-400">The most common operating systems used to visit your short URLs.</p>
            </div>
            <div>
                @php
                    $topOS = $visitService->topOperatingSystems($user);
                @endphp
                @forelse ($topOS as $index => $osData)
                    <div class="flex items-center border-b border-border-200 dark:border-dark-700 last:border-b-0 py-2">
                        <div class="flex-1">
                            <div class="flex justify-between items-center text-sm md:text-base mb-1">
                                <div>
                                    <span class="dark:text-dark-300 dark:font-semibold">#{{ $index + 1 }} -</span>
                                    <span class="text-gray-600 dark:text-dark-400 mt-1">
                                        @if($osData->os) {{ $osData->os }} @else Unknown  @endif
                                    </span>
                                </div>
                                <div class="text-sm font-medium text-blue-600 dark:text-emerald-400">
                                    {{ number_format($osData->total) }}
                                </div>
                            </div>
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

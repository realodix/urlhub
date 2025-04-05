@extends('layouts.frontend')

@section('css_class', 'frontend view_short')
@section('content')
<div class="max-w-7xl mx-auto mb-12">
    <div class="md:w-10/12 mt-6 lg:mt-8 px-4 sm:p-6">
        @if (settings()->retrieve_web_title)
        <div class="text-xl sm:text-2xl lg:text-3xl md:mb-4">{{ $url->title }}</div>
        @endif

        <ul class="mb-4">
            @if (auth()->check() && (auth()->user()->id === $url->user_id || auth()->user()->hasRole('admin')))
            <li class="inline-block pr-4 mt-4 lg:mt-0">
                @svg('icon-chart-line-alt')
                <i>
                    <span title="{{ number_format($visitsCount) }}" class="font-bold">
                        {{ n_abb($visitsCount) }}
                    </span>
                </i>
            </li>
            @endif
            <li class="inline-block pr-4">
                @svg('icon-calendar')
                <i>{{ $createdAt->toFormattedDateString() }}</i>
            </li>
        </ul>
    </div>

    <div class="card card-fluid mt-6 sm:mt-0 px-4 py-5 sm:p-6">
        <div class="grid grid-cols-1 sm:grid-cols-4">
            <div class="grid justify-items-center md:mt-10">
                <img class="qrcode h-fit" src="{{ $qrCode->getDataUri() }}" alt="QR Code">
            </div>

            <div class="col-span-3 pt-4">
                <div class="flex justify-end pr-6 my-[2rem_3rem] sm:my-0">
                    <button id="clipboard_shortlink"
                        title="{{ __('Copy the shortened URL to clipboard') }}"
                        data-clipboard-text="{{ $url->short_url }}"
                        class="btn btn-secondary btn-square btn-sm mr-6"
                    >
                        @svg('icon-clone')
                    </button>

                    @if (auth()->check() && (auth()->user()->id === $url->user_id || auth()->user()->hasRole('admin')))
                        <a href="{{ route('link.edit', $url) }}" title="{{ __('Edit') }}" class="btn btn-secondary btn-square btn-sm mr-6">
                            @svg('icon-edit')
                        </a>
                        <a href="{{ route('link_detail.delete', $url) }}" title="{{ __('Delete') }}"
                            class="btn btn-delete btn-square btn-sm"
                        >
                            @svg('icon-trash')
                        </a>
                    @endif
                </div>

                <p class="text-primary-700 dark:text-emerald-500 font-bold text-xl sm:text-2xl">
                    <a href="{{ $url->short_url }}" target="_blank" id="copy">
                        {{ urlDisplay($url->short_url, scheme: false) }}
                    </a>
                </p>

                <div class="flex gap-x-2 mt-2">
                    <div class="hidden md:block dark:text-dark-400">@svg('icon-arrow-turn-right', 'dark:text-dark-400')</div>
                    <p class="break-all max-w-2xl dark:text-dark-400">
                        <a href="{{ $url->destination }}" target="_blank" rel="noopener noreferrer">
                            {{ $url->destination }}
                        </a>
                    </p>
                </div>

                <div class="mt-22">
                    @if (auth()->check() && (auth()->user()->id === $url->user_id || auth()->user()->hasRole('admin')))
                        <div x-data="{ activeTab: 1 }">
                            <div class="flex space-x-4 -mb-px ml-2">
                                <button @click="activeTab = 1"
                                    :class="{ 'bg-gray-50 dark:bg-dark-800 text-gray-800 dark:text-emerald-500 border-l border-r border-t border-gray-200 dark:border-dark-700': activeTab === 1, 'text-dark-500': activeTab !== 1 }"
                                    class="px-4 py-2 rounded-t-lg font-medium focus:outline-none cursor-pointer"
                                >
                                    Day
                                </button>
                                <button @click="activeTab = 2"
                                    :class="{ 'bg-gray-50 dark:bg-dark-800 text-gray-800 dark:text-emerald-500 border-l border-r border-t border-gray-200 dark:border-dark-700': activeTab === 2, 'text-dark-500': activeTab !== 2 }"
                                    class="px-4 py-2 rounded-t-lg font-medium focus:outline-none cursor-pointer"
                                >
                                    Week
                                </button>
                                <button @click="activeTab = 3"
                                    :class="{ 'bg-gray-50 dark:bg-dark-800 text-gray-800 dark:text-emerald-500 border-l border-r border-t border-gray-200 dark:border-dark-700': activeTab === 3, 'text-dark-500': activeTab !== 3 }"
                                    class="px-4 py-2 rounded-t-lg font-medium focus:outline-none cursor-pointer"
                                >
                                    Month
                                </button>
                            </div>
                            <div class="bg-gray-50 dark:bg-transparent border border-gray-200 dark:border-dark-700 rounded-lg">
                                <div x-show="activeTab === 1">
                                    @livewire(\App\Livewire\Chart\LinkVisitChart::class, ['model' => $url])
                                </div>
                                <div x-show="activeTab === 2">
                                    @livewire(\App\Livewire\Chart\LinkVisitPerWeekChart::class, ['model' => $url])
                                </div>
                                <div x-show="activeTab === 3">
                                    @livewire(\App\Livewire\Chart\LinkVisitPerMonthChart::class, ['model' => $url])
                                </div>
                            </div>
                        </div>

                        <br>

                        <div x-data="{activeTabHorizontal: 'topReferrers'}" class="mb-8 px-2 sm:px-0">
                            <div class="">
                                <ul class="flex space-x-4 -mb-px ml-2">
                                    @php
                                        $activeTabClasses = 'bg-gray-50 dark:bg-dark-800 text-gray-800 dark:text-emerald-500 border-l border-r border-t border-gray-200 dark:border-dark-700';
                                        $inactiveTabClasses = 'text-dark-500';
                                    @endphp
                                    <li class="mr-2">
                                        <button
                                            @click="activeTabHorizontal = 'topReferrers'"
                                            :class="{ '{{ $activeTabClasses }}': activeTabHorizontal === 'topReferrers', '{{ $inactiveTabClasses }}': activeTabHorizontal !== 'topReferrers' }"
                                            class="px-4 py-2 rounded-t-lg font-medium focus:outline-none cursor-pointer"
                                            type="button"
                                        >
                                            Referrers
                                        </button>
                                    </li>
                                    <li class="mr-2">
                                        <button
                                            @click="activeTabHorizontal = 'topBrowsers'"
                                            :class="{ '{{ $activeTabClasses }}': activeTabHorizontal === 'topBrowsers', '{{ $inactiveTabClasses }}': activeTabHorizontal !== 'topBrowsers' }"
                                            class="px-4 py-2 rounded-t-lg font-medium focus:outline-none cursor-pointer"
                                            type="button"
                                        >
                                            Browsers
                                        </button>
                                    </li>
                                    <li class="mr-2">
                                        <button
                                            @click="activeTabHorizontal = 'topOperatingSystems'"
                                            :class="{ '{{ $activeTabClasses }}': activeTabHorizontal === 'topOperatingSystems', '{{ $inactiveTabClasses }}': activeTabHorizontal !== 'topOperatingSystems' }"
                                            class="px-4 py-2 rounded-t-lg font-medium focus:outline-none cursor-pointer"
                                            type="button"
                                        >
                                            OS
                                        </button>
                                    </li>
                                </ul>
                            </div>

                            <!-- Horizontal Tab Content -->
                            <div class="dark:bg-dark-950/50 border border-gray-200 dark:border-dark-800 rounded-lg">
                                <div class="mt-4 px-4">
                                    <div x-show="activeTabHorizontal === 'topReferrers'">
                                        <p class="text-gray-500 dark:text-dark-400 mb-2">
                                            The most common sources of traffic to all short URLs.
                                        </p>
                                        <div>
                                            @php
                                                $topReferrers = $visitService->topReferrers($url);
                                            @endphp
                                            @forelse ($topReferrers as $index => $referrerData)
                                                <div class="flex items-center border-b border-border-200 dark:border-dark-800 last:border-b-0 py-3">
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
                                    <div x-show="activeTabHorizontal === 'topBrowsers'">
                                        <p class="text-gray-500 dark:text-dark-400 mb-2">
                                            The most common browsers used to visit all short URLs.
                                        </p>
                                        <div>
                                            @php
                                                $topBrowsers = $visitService->topBrowsers($url);
                                            @endphp
                                            @forelse ($topBrowsers as $index => $browserData)
                                                <div class="flex items-center border-b border-border-200 dark:border-dark-800 py-3">
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
                                    <div x-show="activeTabHorizontal === 'topOperatingSystems'">
                                        <p class="text-gray-500 dark:text-dark-400 mb-2">
                                            The most common operating systems used to visit all short URLs.
                                        </p>
                                        <div>
                                            @php
                                                $topOS = $visitService->topOperatingSystems($url);
                                            @endphp
                                            @forelse ($topOS as $index => $osData)
                                                <div class="flex items-center border-b border-border-200 dark:border-dark-800 py-3">
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
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-orange-50 border border-border-200 p-4 text-center
                            dark:bg-transparent dark:border-dark-700 dark:text-dark-400"
                        >
                            If this is a link you created from your account, please <a href="{{ route('login') }}" class="text-orange-700 dark:text-orange-600 hover:text-orange-500 dark:hover:text-orange-600/90 font-medium">log in</a> to view the statistics for this link.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

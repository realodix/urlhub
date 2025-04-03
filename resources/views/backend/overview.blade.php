@extends('layouts.backend')

@section('title', __('Overview'))
@section('content')
<div class="page_about container-alt max-w-4xl">
    <div class="content-container card card-fluid">
        <h3 class="text-xl">Users</h3>
        <div class="mt-4 mb-6 px-0">
            <dl class="grid grid-cols-1 gap-2.5 sm:gap-3 md:gap-5 md:grid-cols-2 lg:grid-cols-3">
                @php
                    $userCount = $user->count();
                    $guestUserCount = $user->guestUserCount();
                @endphp
                <div class="card !bg-gray-50 dark:!bg-dark-950/50 !rounded px-4 py-2">
                    <dt class="text-sm font-medium text-gray-600 dark:text-dark-400 md:mt-1">
                        User
                    </dt>
                    <dd class="-mt-1 font-normal text-gray-900 dark:text-dark-300 md:mt-1 md:text-xl">
                        <span title="{{ number_format($userCount) }}">{{ n_abb($userCount) }}</span>
                    </dd>
                </div>
                <div class="card !bg-gray-50 dark:!bg-dark-950/50 !rounded px-4 py-2">
                    <dt class="text-sm font-medium text-gray-600 dark:text-dark-400 md:mt-1">
                        Guest
                    </dt>
                    <dd class="-mt-1 font-normal text-gray-900 dark:text-dark-300 md:mt-1 md:text-xl">
                        <span title="{{ number_format($guestUserCount) }}">{{ n_abb($guestUserCount) }}</span>
                    </dd>
                </div>
            </dl>
        </div>

        <h3 class="text-xl">Links</h3>
        <div class="mt-4 mb-6 px-0">
            <dl class="grid grid-cols-1 gap-2.5 sm:gap-3 md:gap-5 md:grid-cols-2 lg:grid-cols-3">
                @php
                    $linkCount = $url->count();
                    $userLinks = $url->userLinks();
                    $userLinkVisits = $visit->userLinkVisits();
                    $guestUrlCount = $url->guestLinks();
                    $guestLinkVisits = $visit->guestLinkVisits();
                @endphp

                <div class="card !bg-gray-50 dark:!bg-dark-950/50 !rounded px-4 py-2">
                    <dt class="text-sm font-medium text-gray-600 dark:text-dark-400 md:mt-1">
                        Total
                    </dt>
                    <dd class="-mt-1 font-normal text-gray-900 dark:text-dark-300 md:mt-1 md:text-xl">
                        <span title="{{ number_format($linkCount) }}">{{ n_abb($linkCount) }}</span>
                    </dd>
                </div>
                <div class="card !bg-gray-50 dark:!bg-dark-950/50 !rounded px-4 py-2">
                    <dt class="text-sm font-medium text-gray-600 dark:text-dark-400 md:mt-1">
                        User
                    </dt>
                    <dd class="-mt-1 font-normal text-gray-900 dark:text-dark-300 md:mt-1 md:text-xl">
                        <span title="{{ number_format($userLinks) }}">{{ n_abb($userLinks) }}</span>
                        <span title="{{ number_format($userLinkVisits) }}">({{ n_abb($userLinkVisits) }} visits)</span>
                    </dd>
                </div>
                <div class="card !bg-gray-50 dark:!bg-dark-950/50 !rounded px-4 py-2">
                    <dt class="text-sm font-medium text-gray-600 dark:text-dark-400 md:mt-1">
                        Guest
                    </dt>
                    <dd class="-mt-1 font-normal text-gray-900 dark:text-dark-300 md:mt-1 md:text-xl">
                        <span title="{{ number_format($guestUrlCount) }}">{{ n_abb($guestUrlCount) }}</span>
                        <span title="{{ number_format($guestLinkVisits) }}">({{ n_abb($guestLinkVisits) }} visits)</span>
                    </dd>
                </div>
            </dl>
        </div>

        <h3 class="text-xl">Visits</h3>
        <div class="mt-4 mb-6 px-0">
            <dl class="grid grid-cols-1 gap-2.5 sm:gap-3 md:gap-5 md:grid-cols-2 lg:grid-cols-3">
                <div class="card !bg-gray-50 dark:!bg-dark-950/50 !rounded px-4 py-2">
                    <dt class="text-sm font-medium text-gray-600 dark:text-dark-400 md:mt-1">
                        Total
                    </dt>
                    <dd class="-mt-1 font-normal text-gray-900 dark:text-dark-300 md:mt-1 md:text-xl">
                        <span title="{{ number_format($visit->count()) }}">{{ n_abb($visit->count()) }}</span>
                    </dd>
                </div>
                <div class="card !bg-gray-50 dark:!bg-dark-950/50 !rounded px-4 py-2">
                    <dt class="text-sm font-medium text-gray-600 dark:text-dark-400 md:mt-1">
                        User
                    </dt>
                    <dd class="-mt-1 font-normal text-gray-900 dark:text-dark-300 md:mt-1 md:text-xl">
                        <span title="{{ number_format($visit->userVisits()) }}">{{ n_abb($visit->userVisits()) }}</span>
                    </dd>
                </div>
                <div class="card !bg-gray-50 dark:!bg-dark-950/50 !rounded px-4 py-2">
                    <dt class="text-sm font-medium text-gray-600 dark:text-dark-400 md:mt-1">
                        Guest
                    </dt>
                    <dd class="-mt-1 font-normal text-gray-900 dark:text-dark-300 md:mt-1 md:text-xl">
                        <span title="{{ number_format($visit->guestVisits()) }}">{{ n_abb($visit->guestVisits()) }}</span>
                        /
                        <span title="{{ number_format($visit->uniqueGuestVisits()) }}">{{ n_abb($visit->uniqueGuestVisits()) }}</span>
                    </dd>
                </div>
            </dl>
        </div>

        <h3 class="text-xl">Random String</h3>
        <p class="font-light text-sm dark:text-dark-400">Random String Generator for Short URLs</p>
        <div class="mt-4 mb-6 px-0">
            <dl class="grid grid-cols-1 md:grid-flow-col md:auto-cols-auto gap-2.5 sm:gap-3">
                <div class="card !bg-gray-50 dark:!bg-dark-950/50 !rounded px-4 py-2">
                    <dt class="text-sm font-medium text-gray-600 dark:text-dark-400 md:mt-1">
                        Max Unique Strings
                    </dt>
                    <dd class="-mt-1 font-normal text-gray-900 dark:text-dark-300 md:mt-1 md:text-xl">
                        @if ($keyGenService->maxUniqueStrings() === PHP_INT_MAX)
                            (<code>PHP_INT_MAX</code>) {{ number_format(PHP_INT_MAX) }}
                        @else
                            @php
                                $number = strlen($keyGenService::ALPHABET);
                                $powNumber = settings()->keyword_length;
                                $result = number_format($keyGenService->maxUniqueStrings());
                            @endphp

                            ( {{ $number }}<sup>{{ $powNumber }}</sup> ) {{ $result }}
                        @endif
                    </dd>
                </div>
                <div class="card !bg-gray-50 dark:!bg-dark-950/50 !rounded px-4 py-2">
                    <dt class="text-sm font-medium text-gray-600 dark:text-dark-400 md:mt-1 md:w-64">
                        Generated
                    </dt>
                    <dd class="-mt-1 font-normal text-gray-900 dark:text-dark-300 md:mt-1 md:text-xl md:w-64">
                        {{ number_format($keyGenService->keywordCount()) }}
                    </dd>
                </div>
            </dl>
        </div>
    </div>

    <br>

    <div x-data="{activeTabHorizontal: localStorage.getItem('activeTabHorizontal') || 'topUrls',
        init() {this.$watch('activeTabHorizontal', value => localStorage.setItem('activeTabHorizontal', value));}}">
        <div class="mb-8">
            <h2 class="text-2xl font-bold mb-4">Global Top</h2>
            <div class="border-b border-border-300/70 dark:border-dark-700">
                <ul class="flex -mb-px">
                    <li class="mr-2">
                        <button
                            @click="activeTabHorizontal = 'topUrls'"
                            :class="{ 'bg-blue-50 dark:bg-dark-800 text-blue-600 dark:text-emerald-500 border-blue-500 dark:border-emerald-500': activeTabHorizontal === 'topUrls', 'border-transparent text-gray-500 hover:text-gray-700 dark:hover:text-gray-400 hover:border-gray-300 dark:hover:border-emerald-500': activeTabHorizontal !== 'topUrls' }"
                            class="inline-block py-2 px-4 text-sm font-medium text-center border-b-2 rounded-t-lg"
                            type="button"
                        >
                            URLs
                        </button>
                    </li>
                    <li class="mr-2">
                        <button
                            @click="activeTabHorizontal = 'topReferrers'"
                            :class="{ 'bg-blue-50 dark:bg-dark-800 text-blue-600 dark:text-emerald-500 border-blue-500 dark:border-emerald-500': activeTabHorizontal === 'topReferrers', 'border-transparent text-gray-500 hover:text-gray-700 dark:hover:text-gray-400 hover:border-gray-300 dark:hover:border-emerald-500': activeTabHorizontal !== 'topReferrers' }"
                            class="inline-block py-2 px-4 text-sm font-medium text-center border-b-2 rounded-t-lg"
                            type="button"
                        >
                            Referrers
                        </button>
                    </li>
                    <li class="mr-2">
                        <button
                            @click="activeTabHorizontal = 'topBrowsers'"
                            :class="{ 'bg-blue-50 dark:bg-dark-800 text-blue-600 dark:text-emerald-500 border-blue-500 dark:border-emerald-500': activeTabHorizontal === 'topBrowsers', 'border-transparent text-gray-500 hover:text-gray-700 dark:hover:text-gray-400 hover:border-gray-300 dark:hover:border-emerald-500': activeTabHorizontal !== 'topBrowsers' }"
                            class="inline-block py-2 px-4 text-sm font-medium text-center border-b-2 rounded-t-lg"
                            type="button"
                        >
                            Browsers
                        </button>
                    </li>
                    <li class="mr-2">
                        <button
                            @click="activeTabHorizontal = 'topOperatingSystems'"
                            :class="{ 'bg-blue-50 dark:bg-dark-800 text-blue-600 dark:text-emerald-500 border-blue-500 dark:border-emerald-500': activeTabHorizontal === 'topOperatingSystems', 'border-transparent text-gray-500 hover:text-gray-700 dark:hover:text-gray-400 hover:border-gray-300 dark:hover:border-emerald-500': activeTabHorizontal !== 'topOperatingSystems' }"
                            class="inline-block py-2 px-4 text-sm font-medium text-center border-b-2 rounded-t-lg"
                            type="button"
                        >
                            OS
                        </button>
                    </li>
                </ul>
            </div>

            <!-- Horizontal Tab Content -->
            <div class="mt-4">
                <div x-show="activeTabHorizontal === 'topUrls'">
                    <div class="mb-4">
                        <p class="mt-2 text-md text-gray-600 dark:text-dark-400">
                            The most popular short URLs across all users and guests, ranked by visit count.
                        </p>
                    </div>
                    <div class="card card-fluid overflow-hidden px-8 py-4">
                        @php
                            $topUrls = \App\Models\Url::getTopUrlsByVisits();
                        @endphp
                        @forelse ($topUrls as $index => $url)
                            <div class="flex items-center border-b border-gray-200 last:border-b-0 py-3">
                                <div class="flex-1">
                                    <div class="flex justify-between items-center text-sm md:text-base mb-1">
                                        <div>
                                            #{{ $index + 1 }} -
                                            <span class="text-gray-600 mt-1">
                                                <a href="{{ $url->short_url }}" target="_blank" class="text-blue-600 dark:text-emerald-400 hover:underline font-medium">
                                                    {{ $url->keyword }}
                                                </a>

                                                <a href="{{ route('link.edit', $url) }}" class="dark:text-dark-400 hover:underline ">
                                                    {{ Str::limit($url->destination, 70) }}
                                                </a>
                                            </span>
                                        </div>
                                        <span class="text-sm font-medium text-blue-600 dark:text-emerald-400">
                                            {{ $url->visits_count }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-500 dark:text-dark-400">
                                            Created by
                                            <a href="{{ route('dboard.allurl.u-user', $url->author) }}" class="hover:text-blue-600 dark:hover:text-emerald-400">
                                                {{ $url->author->name }}
                                            </a>
                                            {{ $url->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center">Data is not yet available.</p>
                        @endforelse
                    </div>
                </div>
                <div x-show="activeTabHorizontal === 'topReferrers'">
                    <div class="mb-4">
                        <p class="mt-2 text-md text-gray-600 dark:text-dark-400">
                            The most common sources of traffic to all short URLs.
                        </p>
                    </div>
                    <div class="card card-fluid overflow-hidden px-8 py-4">
                        @php
                            $topReferrers = \App\Models\Visit::getTopReferrers();
                        @endphp
                        @forelse ($topReferrers as $index => $referrerData)
                            <div class="flex items-center border-b border-gray-200 last:border-b-0 py-3">
                                <div class="flex-1">
                                    <div class="flex justify-between items-center text-sm md:text-base mb-1">
                                        <div>
                                            #{{ $index + 1 }} -
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
                                        <span class="text-sm font-medium text-blue-600 dark:text-emerald-400">
                                            {{ number_format($referrerData->total) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center">Data is not yet available.</p>
                        @endforelse
                    </div>
                </div>
                <div x-show="activeTabHorizontal === 'topBrowsers'">
                    <div class="mb-4">
                        <p class="mt-2 text-md text-gray-600 dark:text-dark-400">
                            The most common browsers used to visit all short URLs.
                        </p>
                    </div>
                    <div class="card card-fluid overflow-hidden px-8 py-4">
                        @php
                            $topBrowsers = \App\Models\Visit::getTopBrowsers();
                        @endphp
                        @forelse ($topBrowsers as $index => $browserData)
                            <div class="flex items-center border-b border-gray-200 last:border-b-0 py-3">
                                <div class="flex-1">
                                    <div class="flex justify-between items-center text-sm md:text-base mb-1">
                                        <div>
                                            #{{ $index + 1 }} -
                                            <span class="text-gray-600 mt-1">
                                                @if($browserData->browser) {{ $browserData->browser }} @else Unknown @endif
                                            </span>
                                        </div>
                                        <span class="text-sm font-medium text-blue-600 dark:text-emerald-400">
                                            {{ number_format($browserData->total) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center">Data is not yet available.</p>
                        @endforelse
                    </div>
                </div>
                <div x-show="activeTabHorizontal === 'topOperatingSystems'">
                    <div class="mb-4">
                        <p class="mt-2 text-md text-gray-600 dark:text-dark-400">
                            The most common operating systems used to visit all short URLs.
                        </p>
                    </div>
                    <div class="card card-fluid overflow-hidden px-8 py-4">
                        @php
                            $topOperatingSystems = \App\Models\Visit::getTopOperatingSystems();
                        @endphp
                        @forelse ($topOperatingSystems as $index => $osData)
                            <div class="flex items-center border-b border-gray-200 last:border-b-0 py-3">
                                <div class="flex-1">
                                    <div class="flex justify-between items-center text-sm md:text-base mb-1">
                                        <div>
                                            #{{ $index + 1 }} -
                                            <span class="text-gray-600 mt-1">
                                                @if($osData->os) {{ $osData->os }} @else Unknown  @endif
                                            </span>
                                        </div>
                                        <span class="text-sm font-medium text-blue-600 dark:text-emerald-400">
                                            {{ number_format($osData->total) }}
                                        </span>
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

</div>
@endsection

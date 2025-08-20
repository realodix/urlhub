@extends('layouts.backend')

@section('title', 'Overview')
@section('content')
@php
    $userCount = $user->count();
    $guestUsers = $userService->guestUsers();
    $visitCount = $visit->count();
@endphp
<div class="container-alt max-w-4xl">
    <div class="content-container card card-master">
        <h3 class="text-xl">Users</h3>
        <div class="mt-4 mb-6 px-0">
            <dl class="grid grid-cols-1 gap-2.5 sm:gap-3 md:gap-5 md:grid-cols-2 lg:grid-cols-3">
                <div class="card bg-gray-50! dark:bg-dark-950/50! rounded! px-4 py-2">
                    <dt class="text-sm font-medium text-gray-600 dark:text-dark-300 md:mt-1">
                        User
                    </dt>
                    <dd class="-mt-1 font-normal text-gray-900 dark:text-emerald-500 md:mt-1">
                        <span title="{{ number_format($userCount) }}">{{ n_abb($userCount) }}</span>
                    </dd>
                </div>
                <div class="card bg-gray-50! dark:bg-dark-950/50! rounded! px-4 py-2">
                    <dt class="text-sm font-medium text-gray-600 dark:text-dark-300 md:mt-1">
                        Guest
                    </dt>
                    <dd class="-mt-1 font-normal text-gray-900 dark:text-emerald-500 md:mt-1">
                        <span title="{{ number_format($guestUsers) }}">{{ n_abb($guestUsers) }}</span>
                    </dd>
                </div>
            </dl>
        </div>

        <h3 class="text-xl">Links</h3>
        <div class="mt-4 mb-6 px-0">
            <dl class="grid grid-cols-1 gap-2.5 sm:gap-3 md:gap-5 md:grid-cols-2 lg:grid-cols-3">
                @php
                    $linkCount = $url->count();
                    $userLinks = $linkService->userLinks();
                    $visitsOnUserLinks = $visitService->visitsOnUserLinks();
                    $guestUrlCount = $linkService->guestLinks();
                    $visitsOnGuestLinks = $visitService->visitsOnGuestLinks();
                @endphp

                <div class="card bg-gray-50! dark:bg-dark-950/50! rounded! px-4 py-2">
                    <dt class="text-sm font-medium text-gray-600 dark:text-dark-300 md:mt-1">
                        Total
                    </dt>
                    <dd class="-mt-1 font-normal text-gray-900 dark:text-emerald-500 md:mt-1">
                        <span title="{{ number_format($linkCount) }}">{{ n_abb($linkCount) }}</span>
                    </dd>
                </div>
                <div class="card bg-gray-50! dark:bg-dark-950/50! rounded! px-4 py-2">
                    <dt class="text-sm font-medium text-gray-600 dark:text-dark-300 md:mt-1">
                        User
                    </dt>
                    <dd class="-mt-1 font-normal text-gray-900 dark:text-emerald-500 md:mt-1">
                        <span title="{{ number_format($userLinks) }}">{{ n_abb($userLinks) }}</span>
                        <span title="{{ number_format($visitsOnUserLinks) }}">({{ n_abb($visitsOnUserLinks) }} visits)</span>
                    </dd>
                </div>
                <div class="card bg-gray-50! dark:bg-dark-950/50! rounded! px-4 py-2">
                    <dt class="text-sm font-medium text-gray-600 dark:text-dark-300 md:mt-1">
                        Guest
                    </dt>
                    <dd class="-mt-1 font-normal text-gray-900 dark:text-emerald-500 md:mt-1">
                        <span title="{{ number_format($guestUrlCount) }}">{{ n_abb($guestUrlCount) }}</span>
                        <span title="{{ number_format($visitsOnGuestLinks) }}">({{ n_abb($visitsOnGuestLinks) }} visits)</span>
                    </dd>
                </div>
            </dl>
        </div>

        <h3 class="text-xl">Visits</h3>
        <div class="mt-4 mb-6 px-0">
            <dl class="grid grid-cols-1 gap-2.5 sm:gap-3 md:gap-5 md:grid-cols-2 lg:grid-cols-3">
                <div class="card bg-gray-50! dark:bg-dark-950/50! rounded! px-4 py-2">
                    <dt class="text-sm font-medium text-gray-600 dark:text-dark-300 md:mt-1">
                        Total
                    </dt>
                    <dd class="-mt-1 font-normal text-gray-900 dark:text-emerald-500 md:mt-1">
                        {{ n_abb($visitCount) }} visits / {{ n_abb($visitService->visitors()) }} visitors
                    </dd>
                </div>
                <div class="card bg-gray-50! dark:bg-dark-950/50! rounded! px-4 py-2">
                    <dt class="text-sm font-medium text-gray-600 dark:text-dark-300 md:mt-1">
                        User
                    </dt>
                    <dd class="-mt-1 font-normal text-gray-900 dark:text-emerald-500 md:mt-1">
                        {{ n_abb($visitService->userVisits()) }} visits / {{ n_abb($visitService->userVisitors()) }} visitors
                    </dd>
                </div>
                <div class="card bg-gray-50! dark:bg-dark-950/50! rounded! px-4 py-2">
                    <dt class="text-sm font-medium text-gray-600 dark:text-dark-300 md:mt-1">
                        Guest
                    </dt>
                    <dd class="-mt-1 font-normal text-gray-900 dark:text-emerald-500 md:mt-1">
                        {{ n_abb($visitService->guestVisits()) }}</span> visits / {{ n_abb($visitService->guestVisitors()) }} visitors
                    </dd>
                </div>
            </dl>
        </div>

        <x-tabs :tabs="['day' => 'Day', 'week' => 'Week', 'month' => 'Month']" contentClass="p-1">
            <x-slot:day>
                @livewire(\App\Livewire\Chart\LinkVisitChart::class)
            </x-slot>
            <x-slot:week>
                @livewire(\App\Livewire\Chart\LinkVisitPerWeekChart::class)
            </x-slot>
            <x-slot:month>
                @livewire(\App\Livewire\Chart\LinkVisitPerMonthChart::class)
            </x-slot>
        </x-tabs>

        <h3 class="text-xl">Random String</h3>
        <p class="font-light text-sm dark:text-dark-400">
            <strong>Capacity</strong> is the maximum number of unique random keywords that can be generated, minus the weighted value for the space used by the disallowed keywords. <strong>Keyword Space Used</strong> indicating how much of the total capacity has been filled. As this number approaches the capacity, fewer unique keywords are available. Note: based on the current character length configuration.
        </p>
        <div class="mt-4 mb-6 px-0">
            <dl class="grid grid-cols-1 md:grid-flow-col md:auto-cols-auto gap-2.5 sm:gap-3">
                <div class="card bg-gray-50! dark:bg-dark-950/50! rounded! px-4 py-2">
                    <dt class="text-sm font-medium text-gray-600 dark:text-dark-300 md:mt-1">
                        Capacity
                    </dt>
                    <dd class="-mt-1 font-normal text-gray-900 dark:text-emerald-500 md:mt-1">
                        {{ number_format($keyGenService->capacity()) }}
                    </dd>
                </div>
                <div class="card bg-gray-50! dark:bg-dark-950/50! rounded! px-4 py-2">
                    <dt class="text-sm font-medium text-gray-600 dark:text-dark-300 md:mt-1 md:w-64">
                        Keyword Space Used
                    </dt>
                    <dd class="-mt-1 font-normal text-gray-900 dark:text-emerald-500 md:mt-1 md:w-64">
                        {{ number_format($keyGenService->totalKeywordSpaceUsed()) }}
                    </dd>
                </div>
            </dl>
        </div>
    </div>

    <br>

    <x-tabs
        :tabs="['a' => 'URLs', 'b' => 'Referrers', 'c' => 'Browsers', 'd' => 'OS']"
        contentClass="px-4 md:px-8 md:py-4"
    >
        <x-slot:a>
            <p class="text-gray-500 dark:text-dark-400 mb-2">
                The most visited URLs.
            </p>
            <div>
                @php
                    $topUrls = $linkService->getTopUrlsByVisits();
                @endphp
                @forelse ($topUrls as $index => $url)
                    <div class="flex items-center border-b border-border-200 dark:border-dark-700 last:border-b-0 py-2">
                        <div class="flex-1">
                            <div class="flex justify-between items-center text-sm md:text-base mb-1">
                                <div>
                                    <span class="dark:text-emerald-500 dark:font-semibold">#{{ $index + 1 }} -</span>
                                    <span class="text-gray-600 mt-1">
                                        <a href="{{ $url->short_url }}" target="_blank" class="text-blue-600 dark:text-emerald-400 hover:underline font-medium">
                                            {{ $url->keyword }}
                                        </a>
                                        <a href="{{ route('link.edit', $url) }}" class="dark:text-dark-400 hover:underline ">
                                            {{ Str::limit($url->destination, 50) }}
                                        </a>
                                    </span>
                                </div>

                                @php
                                    $percentage = round(($url->visits_count/$visitCount) * 100, 2);
                                @endphp
                                <span class="text-sm font-medium text-blue-600 dark:text-emerald-400">
                                    {{ $url->visits_count }} ({{ $percentage }}%)
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500 dark:text-dark-500">
                                    Created by
                                    <a href="{{ route('dboard.allurl.u-user', $url->author) }}" class="hover:text-blue-600 dark:hover:text-emerald-400">
                                        {{ $url->author->name }}
                                    </a>
                                    {{ $url->created_at->diffForHumans() }}
                                </span>
                                <x-progress-bar percentage="{{ $percentage }}" class="sm:w-36 float-end" />
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center">Data is not yet available.</p>
                @endforelse
            </div>
        </x-slot>
        <x-slot:b>
            <p class="text-gray-500 dark:text-dark-400 mb-2">
                The most common sources of traffic to all short URLs.
            </p>
            <div>
                @php
                    $topReferrers = $visitService->topReferrers();
                @endphp
                @forelse ($topReferrers as $index => $referrerData)
                    <div class="flex items-center border-b border-border-200 dark:border-dark-700 last:border-b-0 py-2">
                        <div class="flex-1">
                            <div class="flex justify-between items-center text-sm md:text-base mb-1">
                                <div>
                                    <span class="dark:text-emerald-500 dark:font-semibold">#{{ $index + 1 }} -</span>
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
                                    $percentage = round(($referrerData->total/$visitCount) * 100, 2);
                                @endphp
                                <div class="text-sm font-medium text-blue-600 dark:text-emerald-400">
                                    {{ number_format($referrerData->total) }} ({{ $percentage }}%)
                                </div>
                            </div>
                            <x-progress-bar percentage="{{ $percentage }}" class="sm:w-36 float-end" />
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center">Data is not yet available.</p>
                @endforelse
            </div>
        </x-slot>
        <x-slot:c>
            <p class="text-gray-500 dark:text-dark-400 mb-2">
                The most common browsers used to visit all short URLs.
            </p>
            <div>
                @php
                    $topBrowsers = $visitService->topBrowsers();
                @endphp
                @forelse ($topBrowsers as $index => $browserData)
                    <div class="flex items-center border-b border-border-200 dark:border-dark-700 last:border-b-0 py-2">
                        <div class="flex-1">
                            <div class="flex justify-between items-center text-sm md:text-base mb-1">
                                <div>
                                    <span class="dark:text-emerald-500 dark:font-semibold">#{{ $index + 1 }} -</span>
                                    <span class="text-gray-600 dark:text-dark-400 mt-1">
                                        @if($browserData->browser) {{ $browserData->browser }} @else Unknown @endif
                                    </span>
                                </div>

                                @php
                                    $percentage = round(($browserData->total/$visitCount) * 100, 2);
                                @endphp
                                <div class="text-sm font-medium text-blue-600 dark:text-emerald-400">
                                    {{ number_format($browserData->total) }} ({{ $percentage }}%)
                                </div>
                            </div>
                            <x-progress-bar percentage="{{ $percentage }}" class="sm:w-36 float-end" />
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center">Data is not yet available.</p>
                @endforelse
            </div>
        </x-slot>
        <x-slot:d>
            <p class="text-gray-500 dark:text-dark-400 mb-2">
                The most common operating systems used to visit all short URLs.
            </p>
            <div>
                @php
                    $topOS = $visitService->topOperatingSystems();
                @endphp
                @forelse ($topOS as $index => $osData)
                    <div class="flex items-center border-b border-border-200 dark:border-dark-700 last:border-b-0 py-2">
                        <div class="flex-1">
                            <div class="flex justify-between items-center text-sm md:text-base mb-1">
                                <div>
                                    <span class="dark:text-emerald-500 dark:font-semibold">#{{ $index + 1 }} -</span>
                                    <span class="text-gray-600 dark:text-dark-400 mt-1">
                                        @if($osData->os) {{ $osData->os }} @else Unknown @endif
                                    </span>
                                </div>

                                @php
                                    $percentage = round(($osData->total/$visitCount) * 100, 2);
                                @endphp
                                <div class="text-sm font-medium text-blue-600 dark:text-emerald-400">
                                    {{ number_format($osData->total) }} ({{ $percentage }}%)
                                </div>
                            </div>
                            <x-progress-bar percentage="{{ $percentage }}" class="sm:w-36 float-end" />
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center">Data is not yet available.</p>
                @endforelse
            </div>
        </x-slot>
    </x-tabs>
</div>
@endsection

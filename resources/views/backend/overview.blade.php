@extends('layouts.backend')

@section('title', 'Overview')
@section('content')
@php
    $userCount = $user->count();
    $guestUsers = $userService->guestUsers();
    $visitCount = $visit->count();

    $linkCount = $url->count();
    $userLinks = $linkService->userLinks();
    $visitsOnUserLinks = $visitService->visitsOnUserLinks();
    $guestUrlCount = $linkService->guestLinks();
    $visitsOnGuestLinks = $visitService->visitsOnGuestLinks();

    $capacity = $keyGenService->capacity();
    $spaceUsed = $keyGenService->totalKeywordSpaceUsed();
    $rawPercentage = $capacity > 0 ? ($spaceUsed / $capacity) * 100 : 0;
    $spacePercentage = match (true) {
        $rawPercentage === 0 || $rawPercentage === 0.0 => '0%',
        $rawPercentage < 0.01 => '< 0.01%',
        default => round($rawPercentage, 2) . '%',
    };
@endphp

<div class="container-alt max-w-4xl">
    <div class="content-container card card-master space-y-8">
        <!-- Section: Users -->
        <div>
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold tracking-tight text-gray-900 dark:text-white">Users</h3>
            </div>
            <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <!-- Registered Users -->
                <div class="rounded-xl border border-gray-200/80 bg-linear-to-br from-white via-white to-indigo-50/40 p-4 dark:border-dark-800 dark:from-dark-900 dark:via-dark-900 dark:to-indigo-950/20">
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <dt class="text-sm font-medium text-gray-600 dark:text-dark-300">
                                User
                            </dt>
                            <span class="rounded-md bg-indigo-50 px-2 py-0.5 text-[11px] font-medium text-indigo-600 dark:bg-indigo-950/70 dark:text-indigo-400">
                                Registered
                            </span>
                        </div>
                        <div class="text-indigo-600 dark:text-indigo-400">
                            @svg('icon-people', 'size-5')
                        </div>
                    </div>

                    <dd class="mt-3 text-2xl font-bold tracking-tight text-gray-900 tabular-nums dark:text-white">
                        <span title="{{ number_format($userCount) }}">{{ n_abb($userCount) }}</span>
                    </dd>

                    <p class="mt-1.5 text-xs text-gray-500 dark:text-dark-400">
                        Registered accounts
                    </p>
                </div>

                <!-- Guest Users -->
                <div class="rounded-xl border border-gray-200/80 bg-linear-to-br from-white via-white to-slate-100/50 p-4 dark:border-dark-800 dark:from-dark-900 dark:via-dark-900 dark:to-slate-800/25">
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <dt class="text-sm font-medium text-gray-600 dark:text-dark-300">
                                Guest
                            </dt>
                            <span class="rounded-md bg-slate-100 px-2 py-0.5 text-[11px] font-medium text-slate-600 dark:bg-slate-800 dark:text-slate-400">
                                Public
                            </span>
                        </div>
                        <div class="text-slate-600 dark:text-slate-400">
                            @svg('icon-people', 'size-5')
                        </div>
                    </div>

                    <dd class="mt-3 text-2xl font-bold tracking-tight text-gray-900 tabular-nums dark:text-white">
                        <span title="{{ number_format($guestUsers) }}">{{ n_abb($guestUsers) }}</span>
                    </dd>

                    <p class="mt-1.5 text-xs text-gray-500 dark:text-dark-400">
                        Non-registered users
                    </p>
                </div>
            </dl>
        </div>

        <!-- Section: Links -->
        <div>
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold tracking-tight text-gray-900 dark:text-white">Links</h3>
            </div>
            <dl class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <!-- Total Links -->
                <div class="rounded-xl border border-gray-200/80 bg-linear-to-br from-white via-white to-emerald-50/40 p-4 dark:border-dark-800 dark:from-dark-900 dark:via-dark-900 dark:to-emerald-950/20">
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <dt class="text-sm font-medium text-gray-600 dark:text-dark-300">
                                Total
                            </dt>
                            <span class="rounded-md bg-emerald-50 px-2 py-0.5 text-[11px] font-medium text-emerald-600 dark:bg-emerald-950/70 dark:text-emerald-400">
                                All
                            </span>
                        </div>
                        <div class="text-emerald-600 dark:text-emerald-400">
                            @svg('icon-link', 'size-5')
                        </div>
                    </div>

                    <dd class="mt-3 text-2xl font-bold tracking-tight text-gray-900 tabular-nums dark:text-white">
                        <span title="{{ number_format($linkCount) }}">{{ n_abb($linkCount) }}</span>
                    </dd>

                    <p class="mt-1.5 text-xs text-gray-500 dark:text-dark-400">
                        All shortened URLs
                    </p>
                </div>

                <!-- User Links -->
                <div class="rounded-xl border border-gray-200/80 bg-linear-to-br from-white via-white to-indigo-50/40 p-4 dark:border-dark-800 dark:from-dark-900 dark:via-dark-900 dark:to-indigo-950/20">
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <dt class="text-sm font-medium text-gray-600 dark:text-dark-300">
                                User
                            </dt>
                            <span class="rounded-md bg-indigo-50 px-2 py-0.5 text-[11px] font-medium text-indigo-600 dark:bg-indigo-950/70 dark:text-indigo-400">
                                Registered
                            </span>
                        </div>
                        <div class="text-indigo-600 dark:text-indigo-400">
                            @svg('icon-link', 'size-5')
                        </div>
                    </div>

                    <dd class="mt-3 text-2xl font-bold tracking-tight text-gray-900 tabular-nums dark:text-white">
                        <span title="{{ number_format($userLinks) }}">{{ n_abb($userLinks) }}</span>
                    </dd>

                    <p class="mt-1.5 text-xs text-gray-500 dark:text-dark-400" title="{{ number_format($visitsOnUserLinks) }}">
                        {{ n_abb($visitsOnUserLinks) }} visits
                    </p>
                </div>

                <!-- Guest Links -->
                <div class="rounded-xl border border-gray-200/80 bg-linear-to-br from-white via-white to-slate-100/50 p-4 dark:border-dark-800 dark:from-dark-900 dark:via-dark-900 dark:to-slate-800/25">
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <dt class="text-sm font-medium text-gray-600 dark:text-dark-300">
                                Guest
                            </dt>
                            <span class="rounded-md bg-slate-100 px-2 py-0.5 text-[11px] font-medium text-slate-600 dark:bg-slate-800 dark:text-slate-400">
                                Public
                            </span>
                        </div>
                        <div class="text-slate-600 dark:text-slate-400">
                            @svg('icon-link', 'size-5')
                        </div>
                    </div>

                    <dd class="mt-3 text-2xl font-bold tracking-tight text-gray-900 tabular-nums dark:text-white">
                        <span title="{{ number_format($guestUrlCount) }}">{{ n_abb($guestUrlCount) }}</span>
                    </dd>

                    <p class="mt-1.5 text-xs text-gray-500 dark:text-dark-400" title="{{ number_format($visitsOnGuestLinks) }}">
                        {{ n_abb($visitsOnGuestLinks) }} visits
                    </p>
                </div>
            </dl>
        </div>

        <!-- Section: Visits -->
        <div>
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold tracking-tight text-gray-900 dark:text-white">Visits</h3>
            </div>
            <dl class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <!-- Total Visits -->
                <div class="rounded-xl border border-gray-200/80 bg-linear-to-br from-white via-white to-amber-50/40 p-4 dark:border-dark-800 dark:from-dark-900 dark:via-dark-900 dark:to-amber-950/20">
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <dt class="text-sm font-medium text-gray-600 dark:text-dark-300">
                                Total
                            </dt>
                            <span class="rounded-md bg-amber-50 px-2 py-0.5 text-[11px] font-medium text-amber-600 dark:bg-amber-950/70 dark:text-amber-400">
                                All
                            </span>
                        </div>
                        <div class="text-amber-600 dark:text-amber-400">
                            @svg('icon-chart-line-alt', 'size-5')
                        </div>
                    </div>

                    <dd class="mt-3 text-2xl font-bold tracking-tight text-gray-900 tabular-nums dark:text-white">
                        {{ n_abb($visitCount) }}
                    </dd>

                    <p class="mt-1.5 text-xs text-gray-500 dark:text-dark-400">
                        {{ n_abb($visitService->visitors()) }} unique visitors
                    </p>
                </div>

                <!-- User Visits -->
                <div class="rounded-xl border border-gray-200/80 bg-linear-to-br from-white via-white to-indigo-50/40 p-4 dark:border-dark-800 dark:from-dark-900 dark:via-dark-900 dark:to-indigo-950/20">
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <dt class="text-sm font-medium text-gray-600 dark:text-dark-300">
                                User
                            </dt>
                            <span class="rounded-md bg-indigo-50 px-2 py-0.5 text-[11px] font-medium text-indigo-600 dark:bg-indigo-950/70 dark:text-indigo-400">
                                Registered
                            </span>
                        </div>
                        <div class="text-indigo-600 dark:text-indigo-400">
                            @svg('icon-chart-line-alt', 'size-5')
                        </div>
                    </div>

                    <dd class="mt-3 text-2xl font-bold tracking-tight text-gray-900 tabular-nums dark:text-white">
                        {{ n_abb($visitService->userVisits()) }}
                    </dd>

                    <p class="mt-1.5 text-xs text-gray-500 dark:text-dark-400">
                        {{ n_abb($visitService->userVisitors()) }} unique visitors
                    </p>
                </div>

                <!-- Guest Visits -->
                <div class="rounded-xl border border-gray-200/80 bg-linear-to-br from-white via-white to-slate-100/50 p-4 dark:border-dark-800 dark:from-dark-900 dark:via-dark-900 dark:to-slate-800/25">
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <dt class="text-sm font-medium text-gray-600 dark:text-dark-300">
                                Guest
                            </dt>
                            <span class="rounded-md bg-slate-100 px-2 py-0.5 text-[11px] font-medium text-slate-600 dark:bg-slate-800 dark:text-slate-400">
                                Public
                            </span>
                        </div>
                        <div class="text-slate-600 dark:text-slate-400">
                            @svg('icon-chart-line-alt', 'size-5')
                        </div>
                    </div>

                    <dd class="mt-3 text-2xl font-bold tracking-tight text-gray-900 tabular-nums dark:text-white">
                        {{ n_abb($visitService->guestVisits()) }}
                    </dd>

                    <p class="mt-1.5 text-xs text-gray-500 dark:text-dark-400">
                        {{ n_abb($visitService->guestVisitors()) }} unique visitors
                    </p>
                </div>
            </dl>
        </div>

        <!-- Chart Tabs (Untouched) -->
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

        <!-- Section: Random String -->
        <div>
            <h3 class="text-lg font-semibold tracking-tight text-gray-900 dark:text-white">Random String</h3>
            <p class="mt-1 text-sm font-light leading-relaxed text-gray-600 dark:text-dark-400">
                <strong class="font-medium text-gray-800 dark:text-dark-200">Capacity</strong> is the total number of unique keywords that can be generated, after accounting for keywords that are not allowed. <strong class="font-medium text-gray-800 dark:text-dark-200">Keyword Space Used</strong> shows how much of this capacity is already occupied. As more keyword space is used, fewer unique keywords remain available.
            </p>
            <br>
            <p class="mt-1 text-sm font-light leading-relaxed text-gray-600 dark:text-dark-400">
                Note: based on the current character length configuration.
            </p>
            <dl class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                <!-- Capacity -->
                <div class="relative overflow-hidden rounded-xl border border-gray-200/80 bg-white p-4 dark:border-dark-800 dark:bg-dark-900">
                    <div class="pointer-events-none absolute -right-6 -top-6 h-22 w-22 rounded-full bg-purple-500/5 dark:bg-purple-400/10"></div>

                    <div class="flex items-start justify-between gap-4">
                        <div class="min-w-0">
                            <dt class="text-sm font-medium text-gray-500 dark:text-dark-400">
                                Capacity
                            </dt>
                            <dd class="mt-2 truncate text-xl font-bold tracking-tight text-gray-900 tabular-nums dark:text-white" title="{{ number_format($capacity) }}">
                                {{ number_format($capacity) }}
                            </dd>
                        </div>
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-purple-200/60 bg-purple-50 text-purple-600 dark:border-purple-800/40 dark:bg-purple-950/60 dark:text-purple-400">
                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                            </svg>
                        </div>
                    </div>

                    <div class="flex items-center gap-1.5 pt-3 text-xs text-gray-500 dark:border-dark-800 dark:text-dark-400">
                        <span class="inline-block h-1.5 w-1.5 rounded-full bg-purple-500"></span>
                        <span>Maximum number of available keywords</span>
                    </div>
                </div>

                <!-- Keyword Space Used -->
                <div class="relative overflow-hidden rounded-xl border border-gray-200/80 bg-white p-4 dark:border-dark-800 dark:bg-dark-900">
                    <div class="pointer-events-none absolute -right-6 -top-6 h-22 w-22 rounded-full bg-rose-500/5 dark:bg-rose-400/10"></div>

                    <div class="flex items-start justify-between gap-4">
                        <div class="min-w-0">
                            <dt class="text-sm font-medium text-gray-500 dark:text-dark-400">
                                Keyword Space Used
                            </dt>
                            <dd class="mt-2 truncate text-xl font-bold tracking-tight text-gray-900 tabular-nums dark:text-white" title="{{ number_format($spaceUsed) }}">
                                {{ number_format($spaceUsed) }}
                            </dd>
                        </div>
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-rose-200/60 bg-rose-50 text-rose-600 dark:border-rose-800/40 dark:bg-rose-950/60 dark:text-rose-400">
                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z" />
                            </svg>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-3 text-xs text-gray-500 dark:border-dark-800 dark:text-dark-400">
                        <div class="flex items-center gap-1.5">
                            <span class="inline-block h-1.5 w-1.5 rounded-full bg-rose-500"></span>
                            <span>{{ $spacePercentage }}</span>
                        </div>
                    </div>
                </div>
            </dl>
        </div>
    </div>

    <br>

    <x-tabs
        :tabs="['a' => 'URLs', 'b' => 'Referrers', 'c' => 'Browsers', 'd' => 'OS']"
        contentClass="px-4 md:px-8 md:py-4"
    >
        {{-- URLs --}}
        <x-slot:a>
            <p class="mb-5 text-sm text-gray-500 dark:text-dark-400">
                The most visited URLs.
            </p>

            @php
                $topUrls = $linkService->getTopUrlsByVisits();
            @endphp

            <div class="divide-y divide-border-200 dark:divide-dark-700">
                @forelse ($topUrls as $index => $url)
                    @php
                        $percentage = $visitCount > 0
                            ? round(($url->visits_count / $visitCount) * 100, 2)
                            : 0;
                    @endphp

                    <div class="group py-4 first:pt-1 last:pb-1">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex min-w-0 flex-1 items-start gap-3">
                                <span class="mt-0.5 w-6 shrink-0 text-xs font-medium tabular-nums text-gray-400 dark:text-dark-500">
                                    {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                </span>

                                <div class="min-w-0 flex-1">
                                    <div class="flex min-w-0 flex-wrap items-center gap-x-2 gap-y-1">
                                        <a
                                            href="{{ $url->short_url }}"
                                            target="_blank"
                                            class="truncate text-sm font-medium text-gray-900 hover:text-blue-600 dark:text-white dark:hover:text-emerald-400"
                                        >
                                            {{ $url->keyword }}
                                        </a>

                                        <a
                                            href="{{ route('link.edit', $url) }}"
                                            class="truncate text-sm text-gray-500 hover:text-gray-900 dark:text-dark-400 dark:hover:text-white"
                                            title="{{ $url->destination }}"
                                        >
                                            {{ Str::limit($url->destination, 50) }}
                                        </a>
                                    </div>

                                    <p class="mt-1 text-xs text-gray-400 dark:text-dark-500">
                                        Created by
                                        <a
                                            href="{{ route('dboard.allurl.u-user', $url->author) }}"
                                            class="text-gray-500 hover:text-blue-600 dark:text-dark-400 dark:hover:text-emerald-400"
                                        >
                                            {{ $url->author->name }}
                                        </a>
                                        <span class="mx-1">&middot;</span>
                                        {{ $url->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>

                            <div class="shrink-0 text-right tabular-nums">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ number_format($url->visits_count) }}
                                </div>
                                <div class="mt-0.5 text-xs text-gray-400 dark:text-dark-500">
                                    {{ $percentage }}%
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 ml-9">
                            <x-progress-bar
                                percentage="{{ $percentage }}"
                                class="w-full"
                            />
                        </div>
                    </div>
                @empty
                    <p class="py-12 text-center text-sm text-gray-400 dark:text-dark-500">
                        Data is not yet available.
                    </p>
                @endforelse
            </div>
        </x-slot:a>

        {{-- Referrers --}}
        <x-slot:b>
            <p class="mb-5 text-sm text-gray-500 dark:text-dark-400">
                The most common sources of traffic to all short URLs.
            </p>

            @php
                $topReferrers = $visitService->topReferrers();
            @endphp

            <div class="divide-y divide-border-200 dark:divide-dark-700">
                @forelse ($topReferrers as $index => $referrerData)
                    @php
                        $percentage = $visitCount > 0
                            ? round(($referrerData->total / $visitCount) * 100, 2)
                            : 0;
                    @endphp

                    <div class="group py-4 first:pt-1 last:pb-1">
                        <div class="flex items-center justify-between gap-4">
                            <div class="flex min-w-0 flex-1 items-center gap-3">
                                <span class="w-6 shrink-0 text-xs font-medium tabular-nums text-gray-400 dark:text-dark-500">
                                    {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                </span>

                                <div class="min-w-0">
                                    @if ($referrerData->referer)
                                        <a
                                            href="{{ $referrerData->referer }}"
                                            target="_blank"
                                            title="{{ $referrerData->referer }}"
                                            class="block truncate text-sm font-medium text-gray-800 hover:text-blue-600 dark:text-dark-200 dark:hover:text-emerald-400"
                                        >
                                            {{ Str::limit($referrerData->referer, 50) }}
                                        </a>
                                    @else
                                        <span class="text-sm font-medium text-gray-500 dark:text-dark-400">
                                            Direct / Unknown
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="shrink-0 text-right tabular-nums">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ number_format($referrerData->total) }}
                                </div>
                                <div class="mt-0.5 text-xs text-gray-400 dark:text-dark-500">
                                    {{ $percentage }}%
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 ml-9">
                            <x-progress-bar
                                percentage="{{ $percentage }}"
                                class="w-full"
                            />
                        </div>
                    </div>
                @empty
                    <p class="py-12 text-center text-sm text-gray-400 dark:text-dark-500">
                        Data is not yet available.
                    </p>
                @endforelse
            </div>
        </x-slot:b>

        {{-- Browsers --}}
        <x-slot:c>
            <p class="mb-5 text-sm text-gray-500 dark:text-dark-400">
                The most common browsers used to visit all short URLs.
            </p>

            @php
                $topBrowsers = $visitService->topBrowsers();
            @endphp

            <div class="divide-y divide-border-200 dark:divide-dark-700">
                @forelse ($topBrowsers as $index => $browserData)
                    @php
                        $percentage = $visitCount > 0
                            ? round(($browserData->total / $visitCount) * 100, 2)
                            : 0;
                    @endphp

                    <div class="py-4 first:pt-1 last:pb-1">
                        <div class="flex items-center justify-between gap-4">
                            <div class="flex min-w-0 flex-1 items-center gap-3">
                                <span class="w-6 shrink-0 text-xs font-medium tabular-nums text-gray-400 dark:text-dark-500">
                                    {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                </span>

                                <span class="truncate text-sm font-medium text-gray-800 dark:text-dark-200">
                                    {{ $browserData->browser ?: 'Unknown' }}
                                </span>
                            </div>

                            <div class="shrink-0 text-right tabular-nums">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ number_format($browserData->total) }}
                                </div>
                                <div class="mt-0.5 text-xs text-gray-400 dark:text-dark-500">
                                    {{ $percentage }}%
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 ml-9">
                            <x-progress-bar
                                percentage="{{ $percentage }}"
                                class="w-full"
                            />
                        </div>
                    </div>
                @empty
                    <p class="py-12 text-center text-sm text-gray-400 dark:text-dark-500">
                        Data is not yet available.
                    </p>
                @endforelse
            </div>
        </x-slot:c>

        {{-- Operating Systems --}}
        <x-slot:d>
            <p class="mb-5 text-sm text-gray-500 dark:text-dark-400">
                The most common operating systems used to visit all short URLs.
            </p>

            @php
                $topOS = $visitService->topOperatingSystems();
            @endphp

            <div class="divide-y divide-border-200 dark:divide-dark-700">
                @forelse ($topOS as $index => $osData)
                    @php
                        $percentage = $visitCount > 0
                            ? round(($osData->total / $visitCount) * 100, 2)
                            : 0;
                    @endphp

                    <div class="py-4 first:pt-1 last:pb-1">
                        <div class="flex items-center justify-between gap-4">
                            <div class="flex min-w-0 flex-1 items-center gap-3">
                                <span class="w-6 shrink-0 text-xs font-medium tabular-nums text-gray-400 dark:text-dark-500">
                                    {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                </span>

                                <span class="truncate text-sm font-medium text-gray-800 dark:text-dark-200">
                                    {{ $osData->os ?: 'Unknown' }}
                                </span>
                            </div>

                            <div class="shrink-0 text-right tabular-nums">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ number_format($osData->total) }}
                                </div>
                                <div class="mt-0.5 text-xs text-gray-400 dark:text-dark-500">
                                    {{ $percentage }}%
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 ml-9">
                            <x-progress-bar
                                percentage="{{ $percentage }}"
                                class="w-full"
                            />
                        </div>
                    </div>
                @empty
                    <p class="py-12 text-center text-sm text-gray-400 dark:text-dark-500">
                        Data is not yet available.
                    </p>
                @endforelse
            </div>
        </x-slot:d>
    </x-tabs>
</div>
@endsection

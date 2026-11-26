@extends('layouts.frontend')

@section('css_class', 'frontend view_short')
@section('content')
@php
    $canManage = auth()->check() && (auth()->user()->id === $url->user_id || auth()->user()->hasRole('admin'));
@endphp

<div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:py-12">
    <!-- Top Link Overview Card -->
    <div class="overflow-hidden rounded-lg border border-gray-200/80 bg-white shadow-xs dark:border-dark-800 dark:bg-dark-900">
        <!-- Card Header: Metadata & Action Buttons -->
        <div class="flex flex-wrap items-center justify-between gap-4 px-6 py-4 bg-gray-50/60 dark:bg-dark-950/40 border-b border-gray-200/80 dark:border-dark-800">
            <div class="flex flex-wrap items-center gap-2.5 text-xs text-gray-500 dark:text-dark-400">
                <span class="inline-flex items-center gap-1.5 rounded-md border border-gray-200/80 bg-white px-2.5 py-1 font-medium text-gray-600 dark:border-dark-700 dark:bg-dark-800 dark:text-dark-300">
                    @svg('icon-calendar', 'size-3.5 text-gray-400 dark:text-dark-400')
                    <span>{{ $createdAt->toFormattedDateString() }}</span>
                </span>

                @if ($canManage)
                    <span class="inline-flex items-center gap-1.5 rounded-md border border-emerald-200/70 bg-emerald-50 px-2.5 py-1 font-medium text-emerald-700 tabular-nums dark:border-emerald-800/40 dark:bg-emerald-950/60 dark:text-emerald-400" title="{{ number_format($visitsCount) }} total clicks">
                        @svg('icon-chart-line-alt', 'size-3.5')
                        <span>{{ n_abb($visitsCount) }} {{ Str::plural('click', $visitsCount) }}</span>
                    </span>
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-2">
                <button
                    id="clipboard_shortlink"
                    type="button"
                    title="Copy the shortened URL to clipboard"
                    data-clipboard-text="{{ $url->short_url }}"
                    class="inline-flex items-center gap-1.5 rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-xs font-medium text-gray-700 shadow-2xs transition hover:border-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-black/10 dark:border-dark-700 dark:bg-dark-800 dark:text-dark-200 dark:hover:border-dark-600 dark:hover:bg-dark-700"
                >
                    @svg('icon-clone', 'size-3.5')
                    <span>Copy</span>
                </button>

                @if ($canManage)
                    <a
                        href="{{ route('link.edit', $url) }}"
                        title="Edit link"
                        class="inline-flex items-center gap-1.5 rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-xs font-medium text-gray-700 shadow-2xs transition hover:border-gray-300 hover:bg-gray-50 dark:border-dark-700 dark:bg-dark-800 dark:text-dark-200 dark:hover:border-dark-600 dark:hover:bg-dark-700"
                    >
                        @svg('icon-edit', 'size-3.5')
                        <span>Edit</span>
                    </a>

                    <form
                        method="post"
                        action="{{ route('link.delete', $url) }}"
                        onsubmit="return confirm('Are you sure you want to delete this link?');"
                        class="inline"
                    >
                        @csrf @method('DELETE')
                        <input type="hidden" name="redirect_to" value="home">
                        <button
                            type="submit"
                            title="Delete link"
                            class="inline-flex items-center gap-1.5 rounded-lg border border-red-200/80 bg-white px-3 py-1.5 text-xs font-medium text-red-600 shadow-2xs transition hover:border-red-300 hover:bg-red-50 dark:border-red-900/50 dark:bg-dark-800 dark:text-red-400 dark:hover:bg-red-950/40"
                        >
                            @svg('icon-trash', 'size-3.5')
                            <span class="hidden sm:inline">Delete</span>
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Card Body: Link Info (Left) & QR Code (Right) -->
        <div class="grid grid-cols-1 gap-6 p-6 md:grid-cols-[1fr_auto] md:items-center md:p-8">
            <div class="min-w-0 space-y-4">
                <h1 class="text-lg font-semibold leading-snug tracking-tight text-gray-900 sm:text-xl dark:text-white">
                    {{ $url->title }}
                </h1>

                <div class="flex items-start gap-3.5">
                    <!-- Favicon Badge -->
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full border border-gray-200/80 bg-gray-50 p-2 dark:border-dark-700 dark:bg-dark-800">
                        <img
                            src="{{ \App\Helpers\Helper::faviconUrl($url->destination) }}"
                            alt=""
                            class="h-6 w-6 rounded-full object-contain"
                        >
                    </div>

                    <div class="min-w-0 flex-1">
                        <!-- Short URL -->
                        <div class="flex items-center gap-2">
                            <a
                                href="{{ $url->short_url }}"
                                target="_blank"
                                id="copy"
                                class="truncate text-xl font-bold tracking-tight text-gray-900 transition sm:text-2xl dark:text-white dark:hover:text-emerald-400"
                            >
                                {{ urlDisplay($url->short_url, scheme: false) }}
                            </a>
                        </div>

                        @if (!$url->isProtected() || $canManage)
                            <!-- Destination URL -->
                            <div class="mt-1.5 flex items-start gap-1.5 text-sm text-gray-500 dark:text-dark-400">
                                <span class="mt-0.5 shrink-0 text-gray-400 dark:text-dark-500">
                                    @svg('icon-arrow-turn-right', 'size-4')
                                </span>
                                <a
                                    href="{{ $url->destination }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="break-all transition hover:text-gray-800 hover:underline dark:hover:text-dark-200"
                                >
                                    {{ $url->destination }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- QR Code Box -->
            <div class="flex flex-col items-center justify-center self-center rounded-2xl border border-gray-200/80 bg-gray-50/70 p-4 dark:border-dark-800 dark:bg-dark-950/50">
                <div class="overflow-hidden rounded-xl bg-white p-2.5 shadow-2xs ring-1 ring-gray-200/60">
                    <img class="qrcode h-28 w-28 sm:h-32 sm:w-32" src="{{ $qrCode->getDataUri() }}" alt="QR Code">
                </div>
                <span class="mt-2 text-[11px] font-medium tracking-wide text-gray-400 uppercase dark:text-dark-500">
                    QR Code
                </span>
            </div>
        </div>
    </div>

    <!-- Analytics Section -->
    <div class="mt-6">
        @if ($canManage)
            <div class="overflow-hidden rounded-lg border border-gray-200/80 bg-white p-5 shadow-xs sm:p-6 dark:border-dark-800 dark:bg-dark-900">
                <x-tabs
                    :tabs="['day' => 'Day', 'week' => 'Week', 'month' => 'Month']"
                    activeTabClass="bg-gray-50! dark:bg-dark-800!"
                    contentClass="p-1"
                >
                    <x-slot:day>
                        @livewire(\App\Livewire\Chart\LinkVisitChart::class, ['model' => $url])
                    </x-slot>
                    <x-slot:week>
                        @livewire(\App\Livewire\Chart\LinkVisitPerWeekChart::class, ['model' => $url])
                    </x-slot>
                    <x-slot:month>
                        @livewire(\App\Livewire\Chart\LinkVisitPerMonthChart::class, ['model' => $url])
                    </x-slot>
                </x-tabs>

                <br>

                <x-tabs
                    :tabs="['a' => 'Referrers', 'b' => 'Browsers', 'c' => 'OS']"
                    activeTabClass="bg-gray-50! dark:bg-dark-800!"
                    contentClass="px-4 md:px-8 md:py-4"
                >
                    {{-- Referrers --}}
                    <x-slot:a>
                        @php
                            $topReferrers = $visitService->topReferrers($url);
                        @endphp

                        @forelse ($topReferrers as $index => $referrerData)
                            @php
                                $percentage = $visitsCount > 0
                                    ? round(($referrerData->total / $visitsCount) * 100, 2)
                                    : 0;
                            @endphp

                            <div class="py-3.5 border-b border-border-200 dark:border-dark-800 last:border-b-0">
                                <div class="flex items-center justify-between gap-4 mb-2">
                                    {{-- Referrer --}}
                                    <div class="min-w-0 flex items-center gap-3">
                                        <span class="shrink-0 text-xs tabular-nums text-gray-400 dark:text-dark-500">
                                            {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                        </span>

                                        @if ($referrerData->referer)
                                            <a
                                                href="{{ $referrerData->referer }}"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                class="truncate text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-dark-200 dark:hover:text-emerald-400 transition-colors"
                                            >
                                                {{ Str::limit($referrerData->referer, 60) }}
                                            </a>
                                        @else
                                            <span class="text-sm font-medium text-gray-500 dark:text-dark-400">
                                                Direct / Unknown
                                            </span>
                                        @endif
                                    </div>

                                    {{-- Visits --}}
                                    <div class="shrink-0 flex items-center gap-2">
                                        <span class="text-sm font-semibold tabular-nums text-gray-900 dark:text-white">
                                            {{ number_format($referrerData->total) }}
                                        </span>

                                        <span class="text-xs tabular-nums text-gray-500 dark:text-dark-400">
                                            {{ $percentage }}%
                                        </span>
                                    </div>
                                </div>

                                {{-- Progress --}}
                                <div class="h-1.5 w-full overflow-hidden rounded-full bg-gray-100 dark:bg-dark-800">
                                    <div
                                        class="h-full rounded-full bg-blue-500 dark:bg-emerald-500 transition-all duration-500"
                                        style="width: {{ $percentage }}%"
                                    ></div>
                                </div>
                            </div>
                        @empty
                            <p class="py-8 text-sm text-center text-gray-500 dark:text-dark-400">
                                Data is not yet available.
                            </p>
                        @endforelse
                    </x-slot:a>

                    {{-- Browsers --}}
                    <x-slot:b>
                        @php
                            $topBrowsers = $visitService->topBrowsers($url);
                        @endphp

                        @forelse ($topBrowsers as $index => $browserData)
                            @php
                                $percentage = $visitsCount > 0
                                    ? round(($browserData->total / $visitsCount) * 100, 2)
                                    : 0;
                            @endphp

                            <div class="py-3.5 border-b border-border-200 dark:border-dark-800 last:border-b-0">
                                <div class="flex items-center justify-between gap-4 mb-2">
                                    {{-- Browser --}}
                                    <div class="min-w-0 flex items-center gap-3">
                                        <span class="shrink-0 text-xs tabular-nums text-gray-400 dark:text-dark-500">
                                            {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                        </span>

                                        <span class="truncate text-sm font-medium text-gray-700 dark:text-dark-200">
                                            {{ $browserData->browser ?: 'Unknown' }}
                                        </span>
                                    </div>

                                    {{-- Visits --}}
                                    <div class="shrink-0 flex items-center gap-2">
                                        <span class="text-sm font-semibold tabular-nums text-gray-900 dark:text-white">
                                            {{ number_format($browserData->total) }}
                                        </span>

                                        <span class="text-xs tabular-nums text-gray-500 dark:text-dark-400">
                                            {{ $percentage }}%
                                        </span>
                                    </div>
                                </div>

                                {{-- Progress --}}
                                <div class="h-1.5 w-full overflow-hidden rounded-full bg-gray-100 dark:bg-dark-800">
                                    <div
                                        class="h-full rounded-full bg-blue-500 dark:bg-emerald-500 transition-all duration-500"
                                        style="width: {{ $percentage }}%"
                                    ></div>
                                </div>
                            </div>
                        @empty
                            <p class="py-8 text-sm text-center text-gray-500 dark:text-dark-400">
                                Data is not yet available.
                            </p>
                        @endforelse
                    </x-slot:b>

                    {{-- Operating Systems --}}
                    <x-slot:c>
                        @php
                            $topOS = $visitService->topOperatingSystems($url);
                        @endphp

                        @forelse ($topOS as $index => $osData)
                            @php
                                $percentage = $visitsCount > 0
                                    ? round(($osData->total / $visitsCount) * 100, 2)
                                    : 0;
                            @endphp

                            <div class="py-3.5 border-b border-border-200 dark:border-dark-800 last:border-b-0">
                                <div class="flex items-center justify-between gap-4 mb-2">
                                    {{-- OS --}}
                                    <div class="min-w-0 flex items-center gap-3">
                                        <span class="shrink-0 text-xs tabular-nums text-gray-400 dark:text-dark-500">
                                            {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                        </span>

                                        <span class="truncate text-sm font-medium text-gray-700 dark:text-dark-200">
                                            {{ $osData->os ?: 'Unknown' }}
                                        </span>
                                    </div>

                                    {{-- Visits --}}
                                    <div class="shrink-0 flex items-center gap-2">
                                        <span class="text-sm font-semibold tabular-nums text-gray-900 dark:text-white">
                                            {{ number_format($osData->total) }}
                                        </span>

                                        <span class="text-xs tabular-nums text-gray-500 dark:text-dark-400">
                                            {{ $percentage }}%
                                        </span>
                                    </div>
                                </div>

                                {{-- Progress --}}
                                <div class="h-1.5 w-full overflow-hidden rounded-full bg-gray-100 dark:bg-dark-800">
                                    <div
                                        class="h-full rounded-full bg-blue-500 dark:bg-emerald-500 transition-all duration-500"
                                        style="width: {{ $percentage }}%"
                                    ></div>
                                </div>
                            </div>
                        @empty
                            <p class="py-8 text-sm text-center text-gray-500 dark:text-dark-400">
                                Data is not yet available.
                            </p>
                        @endforelse
                    </x-slot:c>
                </x-tabs>
            </div>
        @else
            <!-- Guest / Unauthorized Notice -->
            <div class="flex flex-col items-center justify-center rounded-2xl border border-gray-200/80 bg-white px-6 py-10 text-center shadow-xs dark:border-dark-800 dark:bg-dark-900">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl border border-amber-200/70 bg-amber-50 text-amber-600 dark:border-amber-800/40 dark:bg-amber-950/60 dark:text-amber-400">
                    @svg('icon-chart-line-alt', 'size-5')
                </div>
                <h3 class="mt-3 text-sm font-semibold text-gray-900 dark:text-white">
                    Link Analytics Restricted
                </h3>
                <p class="mt-1 max-w-md text-xs leading-relaxed text-gray-500 dark:text-dark-400">
                    If this is a link you created from your account, please
                    <a href="{{ route('login') }}" class="font-medium text-gray-900 underline underline-offset-4 hover:text-emerald-600 dark:text-white dark:hover:text-emerald-400">log in</a>
                    to view detailed click statistics.
                </p>
            </div>
        @endif
    </div>
</div>
@endsection

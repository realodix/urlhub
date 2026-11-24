@extends('layouts.backend')

@section('title', 'Edit "'.$url->keyword.'" ‹ '.str()->title(auth()->user()->name))
@section('content')
@php
    $urlAuthorName = $url->user_id ? $url->author->name : \App\Models\User::GUEST_NAME;
    $advOptSessionId = 'linkOpts-'.substr(session()->getId(), 0, 10).$url->keyword;
@endphp

<div class="container-alt max-w-5xl">
    {{-- Page Header --}}
    <div class="mb-8 flex flex-col gap-4 border-b border-slate-200/80 pb-6 dark:border-dark-800 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-start gap-4 sm:items-center mx-4 sm:mx-0">
            <div class="min-w-0">
                <div class="flex flex-wrap items-center gap-2.5">
                    <h1 class="truncate text-xl font-bold tracking-tight text-slate-900 dark:text-white sm:text-2xl">
                        {{ $url->keyword }}
                    </h1>
                    @if ($url->isExpired())
                        <span class="inline-flex items-center gap-1.5 rounded-full border border-amber-200/80 bg-amber-50 px-2.5 py-0.5 text-xs font-semibold text-amber-700 dark:border-amber-500/30 dark:bg-amber-500/10 dark:text-amber-400">
                            <span class="size-1.5 rounded-full bg-amber-500"></span>
                            Expired
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-200/80 bg-emerald-50 px-2.5 py-0.5 text-xs font-semibold text-emerald-700 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-400">
                            <span class="size-1.5 rounded-full bg-emerald-500"></span>
                            Active
                        </span>
                    @endif
                    @if ($url->password)
                        <span class="inline-flex items-center gap-1 rounded-full border border-blue-200/80 bg-blue-50 px-2.5 py-0.5 text-xs font-medium text-blue-700 dark:border-blue-500/30 dark:bg-blue-500/10 dark:text-blue-400">
                            <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                            </svg>
                            Protected
                        </span>
                    @endif
                </div>
                <p class="mt-1 text-sm text-slate-500 dark:text-dark-400">
                    Manage destination routing, device targeting, password protection, and expiration rules.
                </p>
            </div>
        </div>

        {{-- Quick Action Links --}}
        <div class="flex shrink-0 items-center justify-end gap-2 mx-4 sm:mx-0 mt-4 sm:mt-0">
            <a href="{{ $url->short_url }}" target="_blank"
                class="inline-flex h-9 items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3.5 text-xs font-semibold text-slate-700 shadow-2xs transition-colors hover:bg-slate-50 dark:border-dark-700 dark:bg-dark-900 dark:text-emerald-400 dark:hover:bg-dark-800">
                @svg('open-link-in-new', 'size-3.5')
                <span>Visit Link</span>
            </a>
            <a href="{{ route('link_detail', $url) }}" target="_blank"
                class="inline-flex h-9 items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3.5 text-xs font-semibold text-slate-700 shadow-2xs transition-colors hover:bg-slate-50 dark:border-dark-700 dark:bg-dark-900 dark:text-emerald-400 dark:hover:bg-dark-800">
                @svg('icon-item-detail', 'size-3.5')
                <span>Stats &amp; QR</span>
            </a>
        </div>
    </div>

    {{-- Flash Messages & Expiration Alert --}}
    <div class="mb-6 space-y-4">
        @include('partials.messages')

        @if ($url->isExpired())
            <div role="alert" class="rounded-2xl border border-amber-200/90 bg-amber-50/80 p-4 dark:border-amber-500/30 dark:bg-amber-500/10">
                <div class="flex items-start gap-3">
                    <div class="mt-0.5 flex size-8 shrink-0 items-center justify-center rounded-xl bg-amber-500/15 text-amber-600 dark:text-amber-400">
                        @svg('icon-sign-warning', 'size-4.5!')
                    </div>
                    <div class="text-sm">
                        <p class="font-semibold text-amber-900 dark:text-amber-300">
                            This short link has expired
                        </p>
                        <p class="mt-0.5 text-amber-800/90 dark:text-amber-200/80">
                            @if ($url->expired_url)
                                Visitors are currently being redirected to
                                <a href="{{ $url->expired_url }}" class="font-mono text-xs font-semibold underline decoration-amber-500/60 hover:text-amber-950 dark:hover:text-white" target="_blank" rel="noopener noreferrer">{{ urlDisplay($url->expired_url, 90) }}</a>.
                            @else
                                Visitors can no longer access the destination URL until expiration rules are updated.
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Main Responsive Grid:
         Mobile: Metadata (1) -> Edit Form (2) -> Delete Short Link (3)
         Desktop (lg): Left Column [Metadata + Delete Short Link] | Right Column [Edit Form] --}}
    <div class="grid grid-cols-1 items-start gap-6 lg:grid-cols-12 lg:grid-rows-[auto_1fr] lg:gap-x-8 lg:gap-y-6">
        {{-- 1. Link Metadata Card --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200/90 bg-white shadow-xs dark:border-dark-800 dark:bg-dark-900 lg:col-span-4 lg:col-start-1 lg:row-start-1">
            <div class="border-b border-slate-200/80 px-5 py-3.5 dark:border-dark-800 bg-gray-50/60 dark:bg-dark-950/60">
                <h2 class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-dark-400">
                    Link Metadata
                </h2>
            </div>
            <div class="divide-y divide-slate-200/80 text-sm dark:divide-dark-800">
                <div class="flex items-center justify-between px-5 py-3.5">
                    <span class="flex items-center gap-2 text-slate-500 dark:text-dark-400">
                        @svg('icon-person', 'size-4')
                        <span>Author</span>
                    </span>
                    <a href="{{ route('dboard.allurl.u-user', $urlAuthorName) }}"
                        class="font-semibold text-slate-800 underline decoration-slate-300 decoration-dotted underline-offset-4 transition-colors hover:text-blue-600 dark:text-slate-200 dark:decoration-dark-600 dark:hover:text-emerald-400"
                        title="View all links from this user">
                        {{ $url->author->name }}
                    </a>
                </div>

                <div class="flex items-center justify-between px-5 py-3.5">
                    <span class="flex items-center gap-2 text-slate-500 dark:text-dark-400">
                        @svg('icon-calendar', 'size-4')
                        <span>Created</span>
                    </span>
                    <span class="font-medium text-slate-700 dark:text-slate-300" title="{{ $createdAt->toDayDateTimeString() }} ({{ $createdAt->getOffsetString() }})">
                        {{ $createdAt->diffForHumans() }}
                    </span>
                </div>

                @if ($createdAt != $updatedAt)
                    <div class="flex items-center justify-between px-5 py-3.5">
                        <span class="flex items-center gap-2 text-slate-500 dark:text-dark-400">
                            @svg('icon-updated', 'size-4')
                            <span>Last Updated</span>
                        </span>
                        <span class="font-medium text-slate-700 dark:text-slate-300" title="{{ $updatedAt->toDayDateTimeString() }} ({{ $updatedAt->getOffsetString() }})">
                            {{ $updatedAt->diffForHumans() }}
                        </span>
                    </div>
                @endif
            </div>
        </div>

        {{-- 2. Main Edit Form --}}
        <div class="lg:col-span-8 lg:col-start-5 lg:row-span-2 lg:row-start-1">
            <form method="post" action="{{ route('link.update', $url) }}">
                @csrf
                <div class="overflow-hidden rounded-2xl border border-slate-200/90 bg-white shadow-xs dark:border-dark-800 dark:bg-dark-900">
                    <div class="divide-y divide-slate-200/80 dark:divide-dark-800">
                        {{-- Short URL Summary --}}
                        <div class="p-6">
                            <label class="block text-sm font-semibold text-slate-800 dark:text-slate-200">
                                Short Link
                            </label>

                            <div class="grid grid-cols-2 mt-2.5">
                                <div>
                                    @svg('open-link-in-new')
                                    <span class="text-slate-800 dark:text-emerald-500">
                                        <a href="{{ $url->short_url }}" target="_blank">{{ urlDisplay($url->short_url, scheme: false) }}</a>
                                    </span>
                                </div>
                                <div>
                                    @svg('icon-item-detail')
                                    <span class="text-slate-800 dark:text-emerald-500">
                                        <a href="{{ route('link_detail', $url) }}" target="_blank">{{ urlDisplay(route('link_detail', $url), scheme: false) }}</a>
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Title & Destination URL --}}
                        <div class="space-y-5 p-6">
                            <div>
                                <label for="title" class="block text-sm font-semibold text-slate-800 dark:text-slate-200">
                                    Title
                                </label>
                                <input
                                    id="title"
                                    name="title"
                                    placeholder="e.g. Q4 Product Launch Campaign"
                                    value="{{ $url->title }}"
                                    class="mt-2 h-10 w-full rounded-xl border border-slate-200 bg-white px-3.5 text-sm text-slate-900 placeholder-slate-400 shadow-2xs transition-all focus:border-slate-900 focus:outline-none focus:ring-4 focus:ring-slate-900/10 dark:border-dark-700 dark:bg-dark-950 dark:text-white dark:placeholder-slate-500 dark:focus:border-slate-400 dark:focus:ring-white/10"
                                >
                            </div>

                            <div>
                                <label for="long_url" class="block text-sm font-semibold text-slate-800 dark:text-slate-200">
                                    Destination URL
                                </label>
                                <input
                                    id="long_url"
                                    name="long_url"
                                    required
                                    placeholder="https://example.com/your-long-url"
                                    value="{{ $url->destination }}"
                                    class="mt-2 h-10 w-full rounded-xl border border-slate-200 bg-white px-3.5 font-mono text-sm text-slate-900 placeholder-slate-400 shadow-2xs transition-all focus:border-slate-900 focus:outline-none focus:ring-4 focus:ring-slate-900/10 dark:border-dark-700 dark:bg-dark-950 dark:text-white dark:placeholder-slate-500 dark:focus:border-slate-400 dark:focus:ring-white/10"
                                >
                            </div>
                        </div>

                        {{-- Device Targeting (Android & iOS) --}}
                        <div class="space-y-5 p-6">
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="text-sm font-semibold text-slate-800 dark:text-slate-200">
                                        Device Targeting
                                    </h3>
                                </div>
                                <p class="mt-1 text-xs text-slate-500 dark:text-dark-400">
                                    Redirect mobile visitors automatically to platform-specific app store or deep links.
                                </p>
                            </div>

                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <label for="dest_android" class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
                                        Android Link
                                    </label>
                                    <input
                                        id="dest_android"
                                        name="dest_android"
                                        placeholder="https://play.google.com/store/apps"
                                        value="{{ $url->dest_android }}"
                                        class="mt-1.5 h-10 w-full rounded-xl border border-slate-200 bg-white px-3.5 font-mono text-xs text-slate-900 placeholder-slate-400 shadow-2xs transition-all focus:border-slate-900 focus:outline-none focus:ring-4 focus:ring-slate-900/10 dark:border-dark-700 dark:bg-dark-950 dark:text-white dark:placeholder-slate-500 dark:focus:border-slate-400 dark:focus:ring-white/10"
                                    >
                                </div>

                                <div>
                                    <label for="dest_ios" class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
                                        iOS Link
                                    </label>
                                    <input
                                        id="dest_ios"
                                        name="dest_ios"
                                        placeholder="https://apps.apple.com/us/charts/iphone"
                                        value="{{ $url->dest_ios }}"
                                        class="mt-1.5 h-10 w-full rounded-xl border border-slate-200 bg-white px-3.5 font-mono text-xs text-slate-900 placeholder-slate-400 shadow-2xs transition-all focus:border-slate-900 focus:outline-none focus:ring-4 focus:ring-slate-900/10 dark:border-dark-700 dark:bg-dark-950 dark:text-white dark:placeholder-slate-500 dark:focus:border-slate-400 dark:focus:ring-white/10"
                                    >
                                </div>
                            </div>
                        </div>

                        {{-- Advanced Options Accordion --}}
                        @if($url->user_id !== \App\Models\User::GUEST_ID)
                            <div
                                x-data="{ open: sessionStorage.getItem('{{ $advOptSessionId }}') === 'true' }"
                                x-init="$watch('open', val => sessionStorage.setItem('{{ $advOptSessionId }}', val))"
                                class="p-6"
                            >
                                <button type="button"
                                    x-on:click="open = !open"
                                    class="flex w-full cursor-pointer items-center justify-between rounded-xl border border-slate-200/80 bg-slate-50/80 px-4 py-3 text-left transition-colors hover:bg-slate-100/80 dark:border-dark-700/80 dark:bg-dark-950/60 dark:hover:bg-dark-800/70"
                                >
                                    <div class="flex items-center gap-2.5">
                                        <span class="flex size-7 items-center justify-center rounded-lg bg-white text-slate-600 ring-1 ring-inset ring-slate-200/80 dark:bg-dark-900 dark:text-slate-300 dark:ring-dark-700">
                                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75" />
                                            </svg>
                                        </span>
                                        <div>
                                            <span class="block text-sm font-semibold text-slate-800 dark:text-slate-200">
                                                Advanced Options
                                            </span>
                                            <span class="block text-xs text-slate-500 dark:text-dark-400">
                                                Password protection, expiration limits, and query forwarding
                                            </span>
                                        </div>
                                    </div>
                                    <svg class="size-4 text-slate-400 transition-transform duration-200" x-bind:class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>

                                {{-- Accordion Body --}}
                                <div x-show="open" x-collapse class="mt-5 space-y-6">
                                    {{-- Password Protection Sub-card --}}
                                    <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
                                        <div>
                                            <h4 class="text-sm font-semibold text-slate-800 dark:text-slate-200">
                                                Password Protection
                                            </h4>
                                            <p class="mt-0.5 text-xs text-slate-500 dark:text-dark-400">
                                                Require visitors to enter a password before accessing this link.
                                            </p>
                                        </div>
                                        <div class="flex shrink-0 items-center gap-2">
                                            @if($url->password)
                                                <button type="button" x-on:click="$dispatch('open-modal', 'edit-password-modal')"
                                                    class="inline-flex h-8 cursor-pointer items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 text-xs font-semibold text-slate-700 shadow-2xs transition-colors hover:bg-slate-50 dark:border-dark-700 dark:bg-dark-900 dark:text-slate-200 dark:hover:bg-dark-800">
                                                    @svg('icon-key', 'size-3.5')
                                                    <span>Edit Password</span>
                                                </button>

                                                <button type="button" x-on:click="$dispatch('open-modal', 'remove-password-modal')"
                                                    class="inline-flex h-8 cursor-pointer items-center rounded-lg border border-rose-200 bg-rose-50 px-3 text-xs font-semibold text-rose-600 transition-colors hover:bg-rose-100 dark:border-rose-500/30 dark:bg-rose-500/10 dark:text-rose-400 dark:hover:bg-rose-500/20">
                                                    Remove
                                                </button>
                                            @else
                                                <button type="button" x-on:click="$dispatch('open-modal', 'add-password-modal')"
                                                    class="inline-flex h-8 cursor-pointer items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 text-xs font-semibold text-slate-700 shadow-2xs transition-colors hover:bg-slate-50 dark:border-dark-700 dark:bg-dark-900 dark:text-slate-200 dark:hover:bg-dark-800">
                                                    @svg('icon-key', 'size-3.5')
                                                    <span>Add Password</span>
                                                </button>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Expiration Sub-card --}}
                                    <div class="space-y-4">
                                        <div>
                                            <h4 class="text-sm font-semibold text-slate-800 dark:text-slate-200">
                                                Link Expiration
                                            </h4>
                                            <p class="mt-0.5 text-xs leading-relaxed text-slate-500 dark:text-dark-400">
                                                Set an expiration date or click limit. Once either condition is reached, visitors will see an expiration notice or be redirected to your fallback URL.
                                            </p>
                                        </div>

                                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                            <div>
                                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
                                                    Expiration Date (UTC)
                                                </label>
                                                <x-flat-pickr name="expires_at" value="{{ $url->expires_at }}"
                                                    :options="['time_24hr' => true, 'disableMobile' => true]"
                                                    class="flatpickr-input mt-1.5 h-10 w-full rounded-xl border border-slate-200 bg-white px-3.5 text-sm text-slate-900 shadow-2xs transition-all focus:border-slate-900 focus:outline-none focus:ring-4 focus:ring-slate-900/10 dark:border-dark-700 dark:bg-dark-950 dark:text-white dark:focus:border-slate-400 dark:focus:ring-white/10"
                                                />
                                            </div>

                                            <div>
                                                <label for="expired_clicks" class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
                                                    Click Limit
                                                </label>
                                                <input
                                                    id="expired_clicks"
                                                    name="expired_clicks"
                                                    type="number"
                                                    placeholder="0 (unlimited)"
                                                    value="{{ $url->expired_clicks }}"
                                                    class="mt-1.5 h-10 w-full rounded-xl border border-slate-200 bg-white px-3.5 text-sm text-slate-900 placeholder-slate-400 shadow-2xs transition-all focus:border-slate-900 focus:outline-none focus:ring-4 focus:ring-slate-900/10 dark:border-dark-700 dark:bg-dark-950 dark:text-white dark:placeholder-slate-500 dark:focus:border-slate-400 dark:focus:ring-white/10"
                                                >
                                            </div>
                                        </div>

                                        <div>
                                            <label for="expired_url" class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
                                                Expiration Fallback URL
                                            </label>
                                            <p class="mt-0.5 text-[11px] text-slate-500 dark:text-dark-400">
                                                Visitors will be redirected here after the link expires.
                                            </p>
                                            <input
                                                id="expired_url"
                                                name="expired_url"
                                                placeholder="https://example.com/"
                                                value="{{ $url->expired_url }}"
                                                class="mt-1.5 h-10 w-full rounded-xl border border-slate-200 bg-white px-3.5 font-mono text-xs text-slate-900 placeholder-slate-400 shadow-2xs transition-all focus:border-slate-900 focus:outline-none focus:ring-4 focus:ring-slate-900/10 dark:border-dark-700 dark:bg-dark-950 dark:text-white dark:placeholder-slate-500 dark:focus:border-slate-400 dark:focus:ring-white/10"
                                            >
                                        </div>

                                        <div x-data="{
                                                count: {{ strlen((string) $url->expired_notes) }},
                                                resize(e) { e.style.height = 'auto'; e.style.height = e.scrollHeight + 'px'; }
                                            }"
                                            x-effect="if (open) $nextTick(() => resize($refs.ta_expired_notes))"
                                        >
                                            <div class="flex items-center justify-between">
                                                <label for="expired_notes" class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
                                                    Expiration Notes
                                                </label>
                                                <span class="font-mono text-[11px] text-slate-400 dark:text-dark-400">
                                                    <span x-text="count"></span> / 200
                                                </span>
                                            </div>
                                            <p class="mt-0.5 text-[11px] text-slate-500 dark:text-dark-400">
                                                Custom message displayed on the expired link page.
                                            </p>
                                            <textarea
                                                id="expired_notes"
                                                name="expired_notes"
                                                placeholder="Enter optional note for visitors..."
                                                maxlength="200"
                                                x-ref="ta_expired_notes"
                                                x-on:input="count = $event.target.value.length; resize($event.target)"
                                                class="mt-1.5 min-h-19 w-full resize-none overflow-hidden rounded-xl border border-slate-200 bg-white p-3 text-sm text-slate-900 placeholder-slate-400 shadow-2xs transition-all focus:border-slate-900 focus:outline-none focus:ring-4 focus:ring-slate-900/10 dark:border-dark-700 dark:bg-dark-950 dark:text-white dark:placeholder-slate-500 dark:focus:border-slate-400 dark:focus:ring-white/10"
                                            >{{ $url->expired_notes }}</textarea>
                                        </div>
                                    </div>

                                    {{-- Forward Query Parameters Sub-card --}}
                                    @if (settings()->forward_query && $url->author->forward_query)
                                        <div class="flex items-start justify-between gap-4">
                                            <div class="space-y-2">
                                                <div class="flex items-center gap-2">
                                                    <span class="text-sm font-semibold text-slate-800 dark:text-slate-200">
                                                        Forward Query Parameters
                                                    </span>
                                                </div>
                                                <p class="text-xs leading-relaxed text-gray-500 dark:text-dark-400">
                                                    Forward query parameters from your short link to the destination URL. For example,
                                                    <code class="rounded bg-gray-100 px-1.5 py-0.5 font-mono text-[11px] text-gray-700 dark:bg-dark-800 dark:text-dark-300">https://short.link/abc?utm_medium=social</code>
                                                    will redirect to
                                                    <code class="rounded bg-gray-100 px-1.5 py-0.5 font-mono text-[11px] text-gray-700 dark:bg-dark-800 dark:text-dark-300">https://example.com?utm_medium=social</code>.
                                                </p>
                                            </div>

                                            <label class="switch mt-1 shrink-0">
                                                <input type="checkbox" name="forward_query" value="1" {{ $url->forward_query ? 'checked' : '' }}>
                                                <span class="slider"></span>
                                            </label>
                                        </div>
                                    @else
                                        <input type="hidden" name="forward_query" value="{{ $url->forward_query ? true : false }}">
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Card Footer Action Bar --}}
                    <div class="flex items-center justify-between border-t border-slate-200/80 bg-slate-50/70 px-6 py-4 dark:border-dark-800 dark:bg-dark-950/50">
                        <span class="text-xs text-slate-500 dark:text-dark-400">
                            Changes take effect immediately upon saving.
                        </span>
                        <button type="submit"
                            class="inline-flex h-9 cursor-pointer items-center justify-center rounded-xl bg-slate-900 px-5 text-xs font-semibold text-white shadow-xs transition-all hover:bg-slate-800 active:scale-[0.99] dark:bg-white dark:text-slate-950 dark:hover:bg-slate-100">
                            Save Changes
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- 3. Danger Zone Card --}}
        <div class="overflow-hidden rounded-2xl border border-rose-200/80 bg-white shadow-xs dark:border-rose-500/25 dark:bg-dark-900 lg:col-span-4 lg:col-start-1 lg:row-start-2">
            <div class="p-5">
                <h3 class="text-sm font-semibold text-rose-600 dark:text-rose-400">
                    Delete Short Link
                </h3>
                <p class="mt-1 text-xs leading-relaxed text-slate-500 dark:text-dark-400">
                    Permanently remove this short link and all associated click analytics. This action cannot be undone.
                </p>
                <form method="post" action="{{ route('link.delete', $url) }}"
                    onsubmit="return confirm('Are you sure you want to delete this link?');"
                    class="mt-4">
                    @csrf @method('DELETE')
                    <input type="hidden" name="redirect_to" value="dashboard">
                    <button type="submit"
                        class="inline-flex h-9 w-full cursor-pointer items-center justify-center gap-1.5 rounded-xl border border-rose-200 bg-rose-50 px-3.5 text-xs font-semibold text-rose-600 transition-colors hover:border-red-300 hover:bg-rose-100 dark:border-red-900/50 dark:bg-dark-800 dark:text-red-400 dark:hover:bg-red-500/20">
                        @svg('icon-trash', 'size-3.5')
                        <span>Delete Link</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Password Modals (kept outside the main form) --}}
    @if(!$url->password)
        @include('backend.linkpassword.create')
    @else
        @include('backend.linkpassword.edit')

        <x-modal name="remove-password-modal" maxWidth="md">
            <x-slot:title>Remove Password for <span class="font-semibold dark:text-emerald-500">{{ $url->keyword }}</span></x-slot:title>
            <form method="post" action="{{ route('link.password.delete', $url) }}" class="space-y-6">
                @csrf @method('DELETE')
                <p class="mt-2 mb-2 text-sm font-light dark:text-dark-400">
                    Are you sure you want to remove the password for this link? This action cannot be undone.
                </p>

                <div class="mt-10 flex items-center justify-end gap-2">
                    <button type="button"
                        x-on:click="$dispatch('close-modal', 'remove-password-modal')"
                        class="btn btn-secondary btn-sm"
                    >
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-del btn-sm">Remove Password</button>
                </div>
            </form>
        </x-modal>
    @endif
</div>
@endsection

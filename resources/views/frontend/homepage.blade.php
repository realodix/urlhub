@extends('layouts.frontend')

@section('css_class', 'frontend home')
@section('content')
<div class="relative isolate overflow-hidden pt-14 pb-24 sm:pt-24 sm:pb-32">
    {{-- Background Decorative Elements (Subtle Grid + Ambient Glow) --}}
    <div class="pointer-events-none absolute inset-0 -z-10 overflow-hidden">
        {{-- Radial Glow Top --}}
        <div class="absolute -top-40 left-1/2 -translate-x-1/2 transform-gpu blur-3xl sm:-top-64" aria-hidden="true">
            <div class="aspect-1155/678 w-272 bg-linear-to-tr from-blue-500/20 via-indigo-500/20 to-emerald-400/20 opacity-70 dark:from-blue-600/15 dark:via-indigo-600/15 dark:to-emerald-500/10"
                style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)">
            </div>
        </div>
        {{-- Subtle Dot Pattern --}}
        <svg class="absolute inset-0 h-full w-full stroke-slate-200/70 dark:stroke-slate-800/50 mask-[radial-gradient(100%_70%_at_top_center,white,transparent)]" aria-hidden="true">
            <defs>
                <pattern id="urlhub-grid" width="32" height="32" x="50%" y="-1" patternUnits="userSpaceOnUse">
                    <path d="M.5 32V.5H32" fill="none" />
                </pattern>
            </defs>
            <rect width="100%" height="100%" stroke-width="0" fill="url(#urlhub-grid)" />
        </svg>
    </div>

    <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
        @if (!auth()->check() && !settings()->public_shortening)
            {{-- Private Mode State --}}
            <div class="mx-auto max-w-xl rounded-3xl border border-slate-200/80 bg-white/80 p-10 text-center shadow-xl shadow-slate-200/40 backdrop-blur-md dark:border-dark-800 dark:bg-dark-900/80 dark:shadow-none">
                <div class="mx-auto mb-5 flex size-14 items-center justify-center rounded-2xl bg-blue-500/10 text-blue-600 ring-1 ring-inset ring-blue-500/20 dark:bg-blue-400/10 dark:text-blue-400">
                    <svg class="size-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold tracking-tight text-slate-800 dark:text-slate-100 sm:text-3xl">
                    Private Shortener
                </h2>
                <p class="mt-2 text-base text-slate-500 dark:text-slate-400">
                    Please sign in to your account to start creating and managing short links.
                </p>
                @if (Route::has('login'))
                    <div class="mt-6">
                        <a href="{{ route('login') }}"
                            class="inline-flex items-center gap-2 rounded-xl bg-linear-to-r from-blue-600 to-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-md shadow-blue-500/25 transition-all hover:from-blue-500 hover:to-indigo-500 hover:shadow-lg">
                            Sign In to Continue
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </a>
                    </div>
                @endif
            </div>
        @else
            {{-- Hero Header --}}
            <div class="mx-auto max-w-3xl text-center">
                {{-- Pill Badge --}}
                <div class="mb-6 inline-flex items-center gap-2 rounded-full border border-slate-200/80 bg-white/70 px-3.5 py-1.5 text-xs font-medium text-slate-600 shadow-xs backdrop-blur-sm dark:border-dark-700/80 dark:bg-dark-900/70 dark:text-slate-300">
                    <span class="relative flex size-2">
                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex size-2 rounded-full bg-emerald-500"></span>
                    </span>
                    Fast, clean &amp; open-source URL shortener
                </div>

                {{-- Main Headline --}}
                <h1 class="text-4xl font-extrabold tracking-tight text-slate-800 dark:text-slate-100 sm:text-5xl md:text-6xl md:leading-[1.12]">
                    Give every
                    <span class="bg-linear-to-r from-blue-600 via-indigo-500 to-violet-600 bg-clip-text text-transparent dark:from-blue-400 dark:via-indigo-400 dark:to-purple-400">
                        link
                    </span>
                    the
                    <span class="relative inline-block">
                        <span class="relative z-10">superpowers</span>
                        <svg class="absolute -bottom-1.5 left-0 z-0 h-3 w-full text-amber-400/70 dark:text-amber-400/40" viewBox="0 0 200 12" fill="none" preserveAspectRatio="none">
                            <path d="M2 9.5C55 3.5 145 2.5 198 8.5" stroke="currentColor" stroke-width="4" stroke-linecap="round" />
                        </svg>
                    </span>
                    it deserves
                </h1>

                <p class="mx-auto mt-5 max-w-xl text-base leading-relaxed text-slate-500 dark:text-slate-400 sm:text-lg">
                    Transform long, messy URLs into sleek, memorable links in seconds. Customize your slug and share effortlessly anywhere.
                </p>
            </div>

            {{-- Main Shortener Card --}}
            <div
                class="mx-auto mt-10 max-w-3xl sm:mt-12"
                x-data="{
                    showCustom: {{ old('custom_key') ? 'true' : 'false' }},
                    hasKeywordError: false,
                    checkKeywordError() {
                        if (!this.showCustom) {
                            this.hasKeywordError = false;
                            return;
                        }
                        const wrapper = this.$refs.keywordWrapper;
                        if (!wrapper) return;
                        // Cek apakah ada elemen pesan error (span di dalam root span Livewire, atau tag p)
                        const errorEl = wrapper.querySelector('span > span, p');
                        this.hasKeywordError = !!(errorEl && errorEl.textContent.trim().length > 0);
                    }
                }"
                x-init="
                    const observer = new MutationObserver(() => checkKeywordError());
                    observer.observe($refs.keywordWrapper, { childList: true, subtree: true, characterData: true });
                    $watch('showCustom', () => $nextTick(() => checkKeywordError()));
                "
            >
                <div class="rounded-3xl border border-slate-200/90 bg-white/90 p-3 shadow-2xl shadow-slate-900/6 backdrop-blur-xl transition-all dark:border-dark-800 dark:bg-dark-900/90 dark:shadow-black/40 sm:p-4">
                    <form method="post" action="{{ route('link.create') }}" x-on:submit="if (hasKeywordError) $event.preventDefault()">
                        @csrf

                        {{-- Primary URL Input Bar --}}
                        <div class="flex flex-col gap-2.5 sm:flex-row sm:items-center">
                            <div class="relative flex-1">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 dark:text-slate-500">
                                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" />
                                    </svg>
                                </div>
                                <input
                                    type="url"
                                    name="long_url"
                                    required
                                    value="{{ old('long_url') }}"
                                    placeholder="Paste your long URL here (https://...)"
                                    class="h-14 w-full rounded-2xl border border-slate-200/80 bg-slate-50/70 pl-12 pr-4 text-base text-slate-800 placeholder-slate-400 transition-all duration-200 focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-500/15 dark:border-dark-700 dark:bg-dark-950/70 dark:text-slate-100 dark:placeholder-slate-500 dark:focus:border-indigo-500 dark:focus:bg-dark-950 dark:focus:ring-indigo-500/20 sm:text-lg"
                                >
                            </div>

                            <button
                                type="submit"
                                x-bind:disabled="hasKeywordError"
                                x-bind:class="hasKeywordError
                                    ? 'cursor-not-allowed bg-slate-200 text-slate-400 shadow-none dark:bg-dark-800 dark:text-slate-500'
                                    : 'cursor-pointer bg-linear-to-r from-blue-600 to-indigo-600 text-white shadow-lg shadow-blue-600/25 hover:from-blue-500 hover:to-indigo-500 hover:shadow-xl hover:shadow-blue-600/30 active:scale-[0.99] dark:from-blue-500 dark:to-indigo-500 dark:shadow-indigo-500/20'"
                                class="group inline-flex h-14 shrink-0 items-center justify-center gap-2 rounded-2xl px-7 text-base font-semibold transition-all duration-200"
                            >
                                <span>Shorten</span>
                                <svg
                                    class="size-4 transition-transform duration-200"
                                    x-bind:class="hasKeywordError ? '' : 'group-hover:translate-x-0.5'"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2.2"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                </svg>
                            </button>
                        </div>

                        {{-- Custom Alias Section --}}
                        <div class="mt-3 border-t border-slate-100 pt-3 dark:border-dark-800/80">
                            <div class="flex flex-wrap items-center justify-between gap-2 px-1">
                                <button
                                    type="button"
                                    x-on:click="showCustom = !showCustom"
                                    class="inline-flex cursor-pointer items-center gap-2 rounded-xl px-2.5 py-1.5 text-xs font-semibold text-slate-600 transition-colors hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-dark-800 dark:hover:text-white"
                                >
                                    <span class="flex size-5 items-center justify-center rounded-md bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">
                                        <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                                        </svg>
                                    </span>
                                    <span>Custom alias (optional)</span>
                                    <svg class="size-3.5 text-slate-400 transition-transform duration-200" x-bind:class="{ 'rotate-180': showCustom }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                    </svg>
                                </button>

                                <span class="text-xs text-slate-400 dark:text-slate-500">
                                    Replace clunky URLs with meaningful short links
                                </span>
                            </div>

                            {{-- Expandable Custom URL Box --}}
                            <div
                                x-show="showCustom"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 -translate-y-1"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                class="mt-2.5 rounded-2xl border border-slate-200 bg-slate-50/80 p-3 dark:border-dark-800 dark:bg-dark-950/60"
                            >
                                <div class="flex flex-col gap-2 sm:flex-row sm:items-start">
                                    <span class="inline-flex h-11 shrink-0 items-center rounded-xl border border-slate-200 bg-white px-3.5 font-mono text-sm font-medium text-slate-500 shadow-2xs dark:border-dark-700 dark:bg-dark-900 dark:text-slate-400">
                                        {{ request()->getHttpHost() }}
                                    </span>

                                    <span class="inline-flex h-11 items-center font-mono text-3xl font-medium text-slate-500">
                                        /
                                    </span>

                                    {{-- Livewire Wrapper --}}
                                    <div
                                        x-ref="keywordWrapper"
                                        x-bind:class="hasKeywordError
                                            ? '[&_input]:border-rose-400! focus-within:[&_input]:border-rose-500! focus-within:[&_input]:ring-rose-500/15! dark:[&_input]:border-rose-500/60!'
                                            : '[&_input]:border-slate-200! focus-within:[&_input]:border-emerald-500! focus-within:[&_input]:ring-emerald-500/15! dark:[&_input]:border-dark-700!'"
                                        class="min-w-0 flex-1 [&>span]:block
                                            [&_input]:m-0! [&_input]:h-11! [&_input]:w-full! [&_input]:rounded-xl! [&_input]:border! [&_input]:bg-white! [&_input]:px-3.5! [&_input]:py-0! [&_input]:font-mono [&_input]:text-base! [&_input]:font-semibold [&_input]:text-emerald-600 [&_input]:shadow-2xs [&_input]:transition-all
                                            focus-within:[&_input]:ring-4!
                                            dark:[&_input]:bg-dark-900! dark:[&_input]:text-emerald-400
                                            [&_span_span]:mt-1.5 [&_span_span]:block [&_span_span]:text-left [&_span_span]:text-xs [&_span_span]:font-medium [&_span_span]:text-rose-500 dark:[&_span_span]:text-rose-400
                                            [&_p]:mt-1.5 [&_p]:block [&_p]:text-left [&_p]:text-xs [&_p]:font-medium [&_p]:text-rose-500 dark:[&_p]:text-rose-400"
                                    >
                                        @livewire('validation.validate-custom-keyword')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Flash / Result Messages --}}
                <div class="mt-6">
                    @include('partials.messages')
                </div>
            </div>

            {{-- Accessory Components: Feature Highlights Bento Grid --}}
            <div class="mx-auto mt-16 max-w-4xl sm:mt-20">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div class="group rounded-2xl border border-slate-200 bg-white/60 p-5 backdrop-blur-sm transition-all duration-200 hover:-translate-y-0.5 hover:border-blue-500/30 hover:bg-white hover:shadow-lg hover:shadow-slate-200/40 dark:border-dark-800/80 dark:bg-dark-900/50 dark:hover:border-blue-500/30 dark:hover:bg-dark-900 dark:hover:shadow-none">
                        <div class="mb-3.5 inline-flex size-10 items-center justify-center rounded-xl bg-blue-500/10 text-blue-600 ring-1 ring-inset ring-blue-500/20 transition-transform duration-200 group-hover:scale-105 dark:text-blue-400">
                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                            </svg>
                        </div>
                        <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">Instant Redirects</h3>
                        <p class="mt-1 text-xs leading-relaxed text-slate-500 dark:text-slate-400">
                            Engineered for ultra-low latency redirection so your visitors reach their destination without delay.
                        </p>
                    </div>
                    <div class="group rounded-2xl border border-slate-200 bg-white/60 p-5 backdrop-blur-sm transition-all duration-200 hover:-translate-y-0.5 hover:border-emerald-500/30 hover:bg-white hover:shadow-lg hover:shadow-slate-200/40 dark:border-dark-800/80 dark:bg-dark-900/50 dark:hover:border-emerald-500/30 dark:hover:bg-dark-900 dark:hover:shadow-none">
                        <div class="mb-3.5 inline-flex size-10 items-center justify-center rounded-xl bg-emerald-500/10 text-emerald-600 ring-1 ring-inset ring-emerald-500/20 transition-transform duration-200 group-hover:scale-105 dark:text-emerald-400">
                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                            </svg>
                        </div>
                        <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">Custom Branded Slugs</h3>
                        <p class="mt-1 text-xs leading-relaxed text-slate-500 dark:text-slate-400">
                            Craft readable, trustworthy custom keywords that boost click-through rates across social platforms.
                        </p>
                    </div>
                    <div class="group rounded-2xl border border-slate-200 bg-white/60 p-5 backdrop-blur-sm transition-all duration-200 hover:-translate-y-0.5 hover:border-amber-500/30 hover:bg-white hover:shadow-lg hover:shadow-slate-200/40 dark:border-dark-800/80 dark:bg-dark-900/50 dark:hover:border-amber-500/30 dark:hover:bg-dark-900 dark:hover:shadow-none">
                        <div class="mb-3.5 inline-flex size-10 items-center justify-center rounded-xl bg-amber-500/10 text-amber-600 ring-1 ring-inset ring-amber-500/20 transition-transform duration-200 group-hover:scale-105 dark:text-amber-400">
                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                            </svg>
                        </div>
                        <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">Click Analytics</h3>
                        <p class="mt-1 text-xs leading-relaxed text-slate-500 dark:text-slate-400">
                            Monitor link performance and visitor insights effortlessly from your personal dashboard.
                        </p>
                    </div>
                    <div class="group rounded-2xl border border-slate-200 bg-white/60 p-5 backdrop-blur-sm transition-all duration-200 hover:-translate-y-0.5 hover:border-indigo-500/30 hover:bg-white hover:shadow-lg hover:shadow-slate-200/40 dark:border-dark-800/80 dark:bg-dark-900/50 dark:hover:border-indigo-500/30 dark:hover:bg-dark-900 dark:hover:shadow-none">
                        <div class="mb-3.5 inline-flex size-10 items-center justify-center rounded-xl bg-indigo-500/10 text-indigo-600 ring-1 ring-inset ring-indigo-500/20 transition-transform duration-200 group-hover:scale-105 dark:text-indigo-400">
                            <svg class="size-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M3,11H5V13H3V11M11,5H13V9H11V5M9,11H13V15H11V13H9V11M15,11H17V13H19V11H21V13H19V15H21V19H19V21H17V19H13V21H11V17H15V15H17V13H15V11M19,19V15H17V19H19M15,3H21V9H15V3M17,5V7H19V5H17M3,3H9V9H3V3M5,5V7H7V5H5M3,15H9V21H3V15M5,17V19H7V17H5Z"></path>
                            </svg>
                        </div>
                        <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">QR Codes</h3>
                        <p class="mt-1 text-xs leading-relaxed text-slate-500 dark:text-slate-400">
                            Generate personalized and branded QR codes that connect your audience to your content instantly.
                        </p>
                    </div>
                    <div class="group rounded-2xl border border-slate-200 bg-white/60 p-5 backdrop-blur-sm transition-all duration-200 hover:-translate-y-0.5 hover:border-rose-500/30 hover:bg-white hover:shadow-lg hover:shadow-slate-200/40 dark:border-dark-800/80 dark:bg-dark-900/50 dark:hover:border-rose-500/30 dark:hover:bg-dark-900 dark:hover:shadow-none">
                        <div class="mb-3.5 inline-flex size-10 items-center justify-center rounded-xl bg-rose-500/10 text-rose-600 ring-1 ring-inset ring-rose-500/20 transition-transform duration-200 group-hover:scale-105 dark:text-rose-400">
                            <svg class="size-6" fill="currentColor" viewBox="0 0 24 24">
                                <g><rect fill="none" height="24" width="24" x="0"></rect></g><g><g><g><path d="M12,17c1.1,0,2-0.9,2-2s-0.9-2-2-2s-2,0.9-2,2S10.9,17,12,17z M18,8h-1V6c0-2.76-2.24-5-5-5S7,3.24,7,6v2H6 c-1.1,0-2,0.9-2,2v10c0,1.1,0.9,2,2,2h12c1.1,0,2-0.9,2-2V10C20,8.9,19.1,8,18,8z M8.9,6c0-1.71,1.39-3.1,3.1-3.1 s3.1,1.39,3.1,3.1v2H8.9V6z M18,20H6V10h12V20z"></path></g></g></g>
                            </svg>
                        </div>
                        <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">Protected URL</h3>
                        <p class="mt-1 text-xs leading-relaxed text-slate-500 dark:text-slate-400">
                            Increase security by protecting your link with a password.
                        </p>
                    </div>
                    <div class="group rounded-2xl border border-slate-200 bg-white/60 p-5 backdrop-blur-sm transition-all duration-200 hover:-translate-y-0.5 hover:border-cyan-500/30 hover:bg-white hover:shadow-lg hover:shadow-slate-200/40 dark:border-dark-800/80 dark:bg-dark-900/50 dark:hover:border-cyan-500/30 dark:hover:bg-dark-900 dark:hover:shadow-none">
                        <div class="mb-3.5 inline-flex size-10 items-center justify-center rounded-xl bg-cyan-500/10 text-cyan-600 ring-1 ring-inset ring-cyan-500/20 transition-transform duration-200 group-hover:scale-105 dark:text-cyan-400">
                            <svg class="size-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M0 0h24v24H0V0z" fill="none"></path><path d="M7 11h2v2H7v-2zm14-5v14c0 1.1-.9 2-2 2H5c-1.11 0-2-.9-2-2l.01-14c0-1.1.88-2 1.99-2h1V2h2v2h8V2h2v2h1c1.1 0 2 .9 2 2zM5 8h14V6H5v2zm14 12V10H5v10h14zm-4-7h2v-2h-2v2zm-4 0h2v-2h-2v2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">Custom Validity</h3>
                        <p class="mt-1 text-xs leading-relaxed text-slate-500 dark:text-slate-400">
                            Set date and time limit for your link. The link will be accessible only in specified time.
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

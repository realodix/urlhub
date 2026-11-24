@extends('layouts.backend')

@section('title', 'Settings')
@section('content')
<div class="container-alt">
    <div class="mx-auto max-w-3xl pb-12">
        @include('partials.messages')

        <!-- Page Header -->
        <div class="mb-6 p-[5px_10px] md:p-0">
            <h1 class="text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">
                Settings
            </h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-dark-400">
                Manage general access, short URL generation rules, and redirection behavior.
            </p>
        </div>

        <form method="post" action="{{ route('dboard.settings.update') }}" class="space-y-6">
            @csrf

            <!-- Card 1: General Access -->
            <div class="overflow-hidden rounded-2xl border border-gray-200/80 bg-white dark:border-dark-800 dark:bg-dark-900">
                <div class="bg-gray-50/60 dark:bg-dark-950/40 border-b border-gray-200/80 dark:border-dark-800 px-6 py-4">
                    <h2 class="text-base font-semibold text-gray-900 dark:text-white">
                        General Access
                    </h2>
                    <p class="mt-0.5 text-xs text-gray-500 dark:text-dark-400">
                        Control public permissions for creating links and registering accounts.
                    </p>
                </div>

                <div class="divide-y divide-gray-100 px-6 dark:divide-dark-800">
                    <!-- Guest Shortening -->
                    <div class="flex items-start justify-between gap-6 py-5">
                        <div class="space-y-1">
                            <label for="public_shortening" class="block text-sm font-medium text-gray-900 dark:text-white">
                                Guest Shortening
                            </label>
                            <p class="text-xs leading-relaxed text-gray-500 dark:text-dark-400">
                                Allow visitors without an account to create short links. When disabled, users must sign in to shorten URLs.
                            </p>
                        </div>
                        <div class="shrink-0 pt-0.5">
                            <label class="switch">
                                <input id="public_shortening" type="checkbox" name="public_shortening" value="1" @checked($settings->public_shortening)>
                                <span class="slider"></span>
                            </label>
                        </div>
                    </div>

                    <!-- Account Registration -->
                    <div class="flex items-start justify-between gap-6 py-5">
                        <div class="space-y-1">
                            <label for="public_registration" class="block text-sm font-medium text-gray-900 dark:text-white">
                                Account Registration
                            </label>
                            <p class="text-xs leading-relaxed text-gray-500 dark:text-dark-400">
                                Allow visitors to create a new account. When disabled, only administrators can add new users.
                            </p>
                        </div>
                        <div class="shrink-0 pt-0.5">
                            <label class="switch">
                                <input id="public_registration" type="checkbox" name="public_registration" value="1" @checked($settings->public_registration)>
                                <span class="slider"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2: Short URL -->
            <div class="overflow-hidden rounded-2xl border border-gray-200/80 bg-white dark:border-dark-800 dark:bg-dark-900">
                <div class="bg-gray-50/60 dark:bg-dark-950/40 border-b border-gray-200/80 dark:border-dark-800 px-6 py-4">
                    <h2 class="text-base font-semibold text-gray-900 dark:text-white">
                        Short URL
                    </h2>
                    <p class="mt-0.5 text-xs text-gray-500 dark:text-dark-400">
                        Configure keyword character limits, metadata fetching, and favicon sources.
                    </p>
                </div>

                <div class="divide-y divide-gray-100 px-6 dark:divide-dark-800">
                    <!-- Keyword Length -->
                    <div class="flex flex-col justify-between gap-4 py-5 sm:flex-row sm:items-center">
                        <div class="max-w-md space-y-1">
                            <label for="keyword_length" class="block text-sm font-medium text-gray-900 dark:text-white">
                                Keyword Length
                            </label>
                            <p class="text-xs leading-relaxed text-gray-500 dark:text-dark-400">
                                Number of characters used in generated short URL keywords.
                            </p>
                            <span class="inline-block rounded-md bg-gray-100 px-2 py-0.5 text-[11px] font-medium text-gray-600 tabular-nums dark:bg-dark-800 dark:text-emerald-300">
                                Range: {{ $settings::KEY_LEN_LOW }} – {{ $settings::KEY_LEN_UP }}
                            </span>
                        </div>
                        <div class="w-full sm:w-36 shrink-0">
                            <input
                                id="keyword_length"
                                name="keyword_length"
                                type="number"
                                required
                                value="{{ $settings->key_len }}"
                                class="block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2 text-sm text-gray-900 tabular-nums shadow-xs transition focus:border-black focus:outline-none focus:ring-2 focus:ring-black/10 dark:border-dark-700 dark:bg-dark-800 dark:text-white dark:focus:border-white dark:focus:ring-white/10 @error('keyword_length') border-red-400! focus:border-red-500! focus:ring-red-500/10! @enderror"
                            >
                        </div>
                    </div>

                    <!-- Min. Custom Keyword Length -->
                    <div class="flex flex-col justify-between gap-4 py-5 sm:flex-row sm:items-center">
                        <div class="max-w-md space-y-1">
                            <label for="custom_keyword_min_length" class="block text-sm font-medium text-gray-900 dark:text-white">
                                Min. Custom Keyword Length
                            </label>
                            <p class="text-xs leading-relaxed text-gray-500 dark:text-dark-400">
                                Minimum number of characters allowed when creating a custom keyword.
                            </p>
                            <span class="inline-block rounded-md bg-gray-100 px-2 py-0.5 text-[11px] font-medium text-gray-600 tabular-nums dark:bg-dark-800 dark:text-emerald-300">
                                Range: {{ $settings::CST_KEY_MIN_LEN_LOW }} – {{ $settings::CST_KEY_MIN_LEN_UP }}
                            </span>
                        </div>
                        <div class="w-full sm:w-36 shrink-0">
                            <input
                                id="custom_keyword_min_length"
                                name="custom_keyword_min_length"
                                type="number"
                                required
                                value="{{ $settings->cst_key_min_len }}"
                                class="block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2 text-sm text-gray-900 tabular-nums shadow-xs transition focus:border-black focus:outline-none focus:ring-2 focus:ring-black/10 dark:border-dark-700 dark:bg-dark-800 dark:text-white dark:focus:border-white dark:focus:ring-white/10 @error('custom_keyword_min_length') border-red-400! focus:border-red-500! focus:ring-red-500/10! @enderror"
                            >
                        </div>
                    </div>

                    <!-- Max. Custom Keyword Length -->
                    <div class="flex flex-col justify-between gap-4 py-5 sm:flex-row sm:items-center">
                        <div class="max-w-md space-y-1">
                            <label for="custom_keyword_max_length" class="block text-sm font-medium text-gray-900 dark:text-white">
                                Max. Custom Keyword Length
                            </label>
                            <p class="text-xs leading-relaxed text-gray-500 dark:text-dark-400">
                                Maximum number of characters allowed when creating a custom keyword.
                            </p>
                            <span class="inline-block rounded-md bg-gray-100 px-2 py-0.5 text-[11px] font-medium text-gray-600 tabular-nums dark:bg-dark-800 dark:text-emerald-300">
                                Range: {{ $settings::CST_KEY_MAX_LEN_LOW }} – {{ $settings::CST_KEY_MAX_LEN_UP }}
                            </span>
                        </div>
                        <div class="w-full sm:w-36 shrink-0">
                            <input
                                id="custom_keyword_max_length"
                                name="custom_keyword_max_length"
                                type="number"
                                required
                                value="{{ $settings->cst_key_max_len }}"
                                class="block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2 text-sm text-gray-900 tabular-nums shadow-xs transition focus:border-black focus:outline-none focus:ring-2 focus:ring-black/10 dark:border-dark-700 dark:bg-dark-800 dark:text-white dark:focus:border-white dark:focus:ring-white/10 @error('custom_keyword_max_length') border-red-400! focus:border-red-500! focus:ring-red-500/10! @enderror"
                            >
                        </div>
                    </div>

                    <!-- Autofill Link Title -->
                    <div class="flex items-start justify-between gap-6 py-5">
                        <div class="space-y-1">
                            <label for="autofill_link_title" class="block text-sm font-medium text-gray-900 dark:text-white">
                                Autofill Link Title
                            </label>
                            <p class="text-xs leading-relaxed text-gray-500 dark:text-dark-400">
                                Automatically retrieve and populate the destination website's title when creating a short link.
                            </p>
                        </div>
                        <div class="shrink-0 pt-0.5">
                            <label class="switch">
                                <input id="autofill_link_title" type="checkbox" name="autofill_link_title" value="1" @checked($settings->autofill_link_title)>
                                <span class="slider"></span>
                            </label>
                        </div>
                    </div>

                    <!-- Favicon Provider -->
                    <div class="flex flex-col justify-between gap-4 py-5 sm:flex-row sm:items-center">
                        <div class="max-w-md space-y-1">
                            <label for="favicon_provider" class="block text-sm font-medium text-gray-900 dark:text-white">
                                Favicon Provider
                            </label>
                            <p class="text-xs leading-relaxed text-gray-500 dark:text-dark-400">
                                Select the external service used to fetch website icons for your links.
                            </p>
                        </div>
                        <div class="w-full sm:w-44 shrink-0">
                            <select
                                id="favicon_provider"
                                name="favicon_provider"
                                class="block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2 text-sm text-gray-900 shadow-xs transition focus:border-black focus:outline-none focus:ring-2 focus:ring-black/10 dark:border-dark-700 dark:bg-dark-800 dark:text-white dark:focus:border-white dark:focus:ring-white/10"
                            >
                                <option value="duckduckgo" @selected($settings->favicon_provider == 'duckduckgo')>DuckDuckGo</option>
                                <option value="google" @selected($settings->favicon_provider == 'google')>Google</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 3: Redirection -->
            <div class="overflow-hidden rounded-2xl border border-gray-200/80 bg-white dark:border-dark-800 dark:bg-dark-900">
                <div class="bg-gray-50/60 dark:bg-dark-950/40 border-b border-gray-200/80 dark:border-dark-800 px-6 py-4">
                    <h2 class="text-base font-semibold text-gray-900 dark:text-white">
                        Redirection
                    </h2>
                    <p class="mt-0.5 text-xs text-gray-500 dark:text-dark-400">
                        Customize URL parameter forwarding and visitor traffic filtering.
                    </p>
                </div>

                <div class="divide-y divide-gray-100 px-6 dark:divide-dark-800">
                    <!-- Forward Query Parameters -->
                    <div class="flex items-start justify-between gap-6 py-5">
                        <div class="space-y-1.5">
                            <label for="forward_query" class="block text-sm font-medium text-gray-900 dark:text-white">
                                Forward Query Parameters
                            </label>
                            <p class="text-xs leading-relaxed text-gray-500 dark:text-dark-400">
                                Forward query parameters from your short link to the destination URL. For example,
                                <code class="rounded bg-gray-100 px-1.5 py-0.5 font-mono text-[11px] text-gray-700 dark:bg-dark-800 dark:text-dark-300">https://short.link/abc?utm_medium=social</code>
                                will redirect to
                                <code class="rounded bg-gray-100 px-1.5 py-0.5 font-mono text-[11px] text-gray-700 dark:bg-dark-800 dark:text-dark-300">https://example.com?utm_medium=social</code>.
                            </p>
                        </div>
                        <div class="shrink-0 pt-0.5">
                            <label class="switch">
                                <input id="forward_query" type="checkbox" name="forward_query" value="1" @checked($settings->forward_query)>
                                <span class="slider"></span>
                            </label>
                        </div>
                    </div>

                    <!-- Track Bot Visits -->
                    <div class="flex items-start justify-between gap-6 py-5">
                        <div class="space-y-1">
                            <label for="track_bot_visits" class="block text-sm font-medium text-gray-900 dark:text-white">
                                Track Bot Visits
                            </label>
                            <p class="text-xs leading-relaxed text-gray-500 dark:text-dark-400">
                                When enabled, visits from web crawlers and bots will be recorded in the database and included in your link statistics. If disabled, these visits will be ignored and not stored.
                            </p>
                        </div>
                        <div class="shrink-0 pt-0.5">
                            <label class="switch">
                                <input id="track_bot_visits" type="checkbox" name="track_bot_visits" value="1" @checked($settings->track_bot_visits)>
                                <span class="slider"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sticky Global Action Bar -->
            <div class="sticky bottom-4 z-20 flex items-center justify-between rounded-2xl border border-gray-200/90 bg-white/90 px-6 py-3.5 shadow-lg backdrop-blur-md dark:border-dark-700/80 dark:bg-dark-900/90">
                <p class="text-xs text-gray-500 dark:text-dark-400">
                    Changes take effect immediately after saving.
                </p>
                <button
                    type="submit"
                    class="inline-flex shrink-0 items-center justify-center rounded-lg border border-black bg-black px-4 py-2 text-sm font-medium text-white shadow-xs transition hover:bg-neutral-800 focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 dark:border-white dark:bg-white dark:text-black dark:hover:bg-neutral-200"
                >
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

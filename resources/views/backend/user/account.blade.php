@extends('layouts.backend')

@section('title', 'Edit Account "'.$user->name.'" ‹ '.str()->title(auth()->user()->name))
@section('content')
<div class="container-alt max-w-5xl">
    {{-- Page Header --}}
    <div class="mb-8 flex flex-col gap-4 border-b border-slate-200/80 pb-6 dark:border-dark-800 sm:flex-row sm:items-center sm:justify-between">
        <div class="mx-4 flex items-center gap-4 sm:mx-0">
            {{-- Avatar Initials Badge --}}
            <div class="flex size-12 shrink-0 items-center justify-center rounded-2xl border border-slate-200/80 bg-white text-lg font-bold uppercase text-slate-700 shadow-2xs dark:border-dark-700 dark:bg-dark-900 dark:text-slate-200">
                {{ mb_substr($user->name, 0, 2) }}
            </div>
            <div>
                <div class="flex items-center gap-2.5">
                    <h1 class="text-xl font-bold tracking-tight text-slate-900 dark:text-white sm:text-2xl">
                        Account Settings
                    </h1>
                    <span class="inline-flex items-center rounded-full border border-slate-200 bg-slate-100/80 px-2.5 py-0.5 font-mono text-xs font-medium text-slate-600 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-400">
                        {{ '@' . $user->name }}
                    </span>
                </div>
                <p class="mt-1 text-sm text-slate-500 dark:text-dark-400">
                    Manage your personal profile details, regional timezone, and link forwarding preferences.
                </p>
            </div>
        </div>

        {{-- Single Global Save Button --}}
        <div class="mx-4 flex shrink-0 justify-end sm:mx-0 mt-4 sm:mt-0">
            <button type="submit" form="account-settings-form"
                class="inline-flex h-9 cursor-pointer items-center justify-center rounded-xl bg-slate-900 px-4 text-xs font-semibold text-white shadow-xs transition-all hover:bg-slate-800 active:scale-[0.99] dark:bg-white dark:text-slate-950 dark:hover:bg-slate-100">
                Save Changes
            </button>
        </div>
    </div>

    {{-- Flash / Validation Messages --}}
    <div class="mb-6">
        @include('partials.messages')
    </div>

    <form id="account-settings-form" method="post" action="{{ route('user.update', $user) }}" class="space-y-8">
        @csrf

        {{-- Section 1: Profile & Regional --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-12 lg:gap-8">
            {{-- Left Column: Section Description --}}
            <div class="mx-4 lg:col-span-4 sm:mx-0">
                <h2 class="text-base font-semibold text-slate-900 dark:text-white">
                    General Information
                </h2>
                <p class="mt-1 text-sm leading-relaxed text-slate-500 dark:text-dark-400">
                    Your account identity and regional preferences used across dashboard reports and timestamps.
                </p>
            </div>

            {{-- Right Column --}}
            <div class="lg:col-span-8">
                <div class="overflow-hidden rounded-2xl border border-slate-200/90 bg-white shadow-xs dark:border-dark-800 dark:bg-dark-900">
                    <div class="divide-y divide-slate-200/80 dark:divide-dark-800">
                        {{-- Username Field --}}
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <label for="username" class="block text-sm font-semibold text-slate-800 dark:text-slate-200">
                                    Username
                                </label>
                            </div>
                            <div class="mt-2.5 max-w-md">
                                <input
                                    id="username"
                                    name="name"
                                    value="{{ $user->name }}"
                                    disabled
                                    class="h-10 w-full cursor-not-allowed rounded-xl border border-slate-200/80 bg-slate-50/90 px-3.5 font-mono text-sm text-slate-500 dark:border-dark-700/80 dark:bg-dark-950/70 dark:text-slate-400"
                                >
                            </div>
                        </div>

                        {{-- Email Address Field --}}
                        <div class="p-6">
                            <label for="email" class="block text-sm font-semibold text-slate-800 dark:text-slate-200">
                                Email Address
                            </label>
                            <p class="mt-1 text-xs text-slate-500 dark:text-dark-400">
                                Used for account notifications and password recovery. Leave blank to keep <span class="font-medium text-slate-700 dark:text-slate-300">{{ $user->email }}</span>.
                            </p>
                            <div class="relative mt-3 max-w-md">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 dark:text-slate-500">
                                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                                    </svg>
                                </div>
                                <input
                                    id="email"
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    placeholder="{{ $user->email }}"
                                    class="h-10 w-full rounded-xl border border-slate-200 bg-white pl-10 pr-3.5 text-sm text-slate-900 placeholder-slate-400 shadow-2xs transition-all focus:border-slate-900 focus:outline-none focus:ring-4 focus:ring-slate-900/10 dark:border-dark-700 dark:bg-dark-950 dark:text-white dark:placeholder-slate-500 dark:focus:border-slate-400 dark:focus:ring-white/10"
                                >
                            </div>
                        </div>

                        {{-- Timezone Field --}}
                        <div class="p-6">
                            <label class="block text-sm font-semibold text-slate-800 dark:text-slate-200">
                                Timezone
                            </label>
                            <p class="mt-1 text-xs text-slate-500 dark:text-dark-400">
                                Select the timezone offset that you want to use for your account analytics and link schedules.
                            </p>
                            <div class="mt-3 max-w-md
                                [&_select]:h-10! [&_select]:w-full! [&_select]:rounded-xl! [&_select]:border! [&_select]:border-slate-200! [&_select]:bg-white! [&_select]:px-3.5! [&_select]:text-sm! [&_select]:text-slate-900! [&_select]:shadow-2xs [&_select]:transition-all
                                focus-within:[&_select]:border-slate-900! focus-within:[&_select]:ring-4! focus-within:[&_select]:ring-slate-900/10!
                                dark:[&_select]:border-dark-700! dark:[&_select]:bg-dark-950! dark:[&_select]:text-white! dark:focus-within:[&_select]:border-slate-400! dark:focus-within:[&_select]:ring-white/10!">
                                {!! $timezoneList !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 2: Link Behavior (Only shown if settings()->forward_query is enabled) --}}
        @if (settings()->forward_query)
            <div class="grid grid-cols-1 gap-6 border-t border-slate-200/80 pt-8 dark:border-dark-800 lg:grid-cols-12 lg:gap-8">
                {{-- Left Column --}}
                <div class="mx-4 lg:col-span-4 sm:mx-0">
                    <h2 class="text-base font-semibold text-slate-900 dark:text-white">
                        Link Behavior
                    </h2>
                    <p class="mt-1 text-sm leading-relaxed text-slate-500 dark:text-dark-400">
                        Configure default redirection rules and query parameter handling for your shortened URLs.
                    </p>
                </div>

                {{-- Right Column --}}
                <div class="lg:col-span-8">
                    <div class="overflow-hidden rounded-2xl border border-slate-200/90 bg-white p-6 shadow-xs dark:border-dark-800 dark:bg-dark-900">
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
                                <input type="checkbox" name="forward_query" value="1" {{ $user->forward_query ? 'checked' : '' }}>
                                <span class="slider"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <input type="hidden" name="forward_query" value="{{ $user->forward_query ? true : false }}">
        @endif
    </form>
</div>
@endsection

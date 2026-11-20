@extends('layouts.frontend')

@section('css_class', 'frontend home')
@section('content')
<div class="relative isolate flex min-h-[calc(100vh-8rem)] items-center justify-center overflow-hidden px-4 py-14 sm:px-6 lg:px-8">
    {{-- Dub-style Subtle Grid & Warm Ambient Glow --}}
    <div class="pointer-events-none absolute inset-0 -z-10 overflow-hidden">
        <div class="absolute -top-32 left-1/2 -translate-x-1/2 transform-gpu blur-3xl" aria-hidden="true">
            <div class="aspect-1155/678 w-248 bg-linear-to-tr from-amber-500/20 via-orange-500/15 to-rose-400/15 opacity-70 dark:from-amber-500/15 dark:via-orange-500/10 dark:to-rose-500/10"
                style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)">
            </div>
        </div>
        <svg class="absolute inset-0 h-full w-full stroke-slate-200/75 dark:stroke-dark-800/50 mask-[radial-gradient(85%_65%_at_center,white,transparent)]" aria-hidden="true">
            <defs>
                <pattern id="dub-grid-expired" width="32" height="32" x="50%" y="-1" patternUnits="userSpaceOnUse">
                    <path d="M.5 32V.5H32" fill="none" />
                </pattern>
            </defs>
            <rect width="100%" height="100%" stroke-width="0" fill="url(#dub-grid-expired)" />
        </svg>
    </div>

    {{-- Main Card --}}
    <div class="w-full max-w-lg rounded-3xl border border-slate-200/90 bg-white/90 p-8 text-center shadow-2xl shadow-slate-900/6 backdrop-blur-xl dark:border-dark-800 dark:bg-dark-900/90 dark:shadow-black/50 sm:p-11">
        {{-- Status Pill --}}
        <div class="mb-6 inline-flex items-center gap-2 rounded-full border border-amber-200/80 bg-amber-50/90 px-3 py-1 text-xs font-semibold text-amber-700 dark:border-amber-500/25 dark:bg-amber-500/10 dark:text-amber-400">
            <span class="size-1.5 rounded-full bg-amber-500"></span>
            Link Unavailable
        </div>

        {{-- Icon Container --}}
        <div class="mx-auto mb-6 flex size-20 items-center justify-center rounded-3xl border border-amber-200/70 bg-linear-to-b from-amber-50 to-orange-50/60 shadow-xs dark:border-amber-500/20 dark:from-amber-500/10 dark:to-orange-500/5">
            <x-icon-link-expired class="size-11! text-amber-600 dark:text-amber-400" />
        </div>

        {{-- Heading --}}
        <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white sm:text-4xl">
            This link has expired
        </h1>

        {{-- Expired URL Pill --}}
        <div class="mt-5 inline-flex max-w-full items-center gap-2 rounded-xl border border-slate-200/80 bg-slate-50 px-3.5 py-2 font-mono text-xs font-semibold text-amber-700 dark:border-dark-700 dark:bg-dark-950 dark:text-amber-400 sm:text-sm">
            <svg class="size-4 shrink-0 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" />
            </svg>
            <span class="truncate">{{ urlDisplay($url->short_url, scheme: false) }}</span>
        </div>

        {{-- Notes / Description --}}
        @if ($url->expired_notes)
            <div class="mt-6 rounded-2xl border border-slate-200/70 bg-slate-50/70 p-4 text-sm leading-relaxed text-slate-600 dark:border-dark-800 dark:bg-dark-950/60 dark:text-slate-300">
                {{ $url->expired_notes }}
            </div>
        @else
            <p class="mx-auto mt-5 max-w-sm text-sm leading-relaxed text-slate-500 dark:text-slate-400 sm:text-base">
                The creator of this short link set an expiration limit that has now passed. Please contact the link owner for an updated URL.
            </p>
        @endif

        {{-- Action Button --}}
        <div class="mt-8 border-t border-slate-100 pt-6 dark:border-dark-800/80">
            <a href="{{ url('/') }}"
                class="group inline-flex h-11 items-center justify-center gap-2 rounded-xl bg-slate-900 px-5 text-sm font-semibold text-white shadow-sm transition-all hover:bg-slate-800 active:scale-[0.99] dark:bg-white dark:text-slate-950 dark:hover:bg-slate-100">
                <svg class="size-4 transition-transform duration-200 group-hover:-translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                <span>Create a new short link</span>
            </a>
        </div>
    </div>
</div>
@endsection

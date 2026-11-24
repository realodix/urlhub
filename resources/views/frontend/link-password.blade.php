<!DOCTYPE html>
<html lang="en">
<head>
    <title>{{ config('app.name') }} - Private Link</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @livewireStyles
    @vite(['resources/css/main.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-[#fafafa] font-sans antialiased text-slate-900 selection:bg-blue-500/20 selection:text-blue-700 dark:bg-dark-950 dark:text-slate-100">
    <div class="relative isolate flex min-h-screen flex-col items-center justify-center overflow-hidden px-4 py-12 sm:px-6 lg:px-8">
        {{-- Subtle Grid & Ambient Radial Glow --}}
        <div class="pointer-events-none absolute inset-0 -z-10 overflow-hidden">
            <div class="absolute -top-36 left-1/2 -translate-x-1/2 transform-gpu blur-3xl" aria-hidden="true">
                <div class="aspect-1155/678 w-240 bg-linear-to-tr from-blue-500/20 via-indigo-500/15 to-emerald-400/15 opacity-75 dark:from-blue-600/15 dark:via-indigo-600/10 dark:to-emerald-500/10"
                    style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)">
                </div>
            </div>
            <svg class="absolute inset-0 h-full w-full stroke-slate-200/75 dark:stroke-dark-800/50 mask-[radial-gradient(85%_65%_at_center,white,transparent)]" aria-hidden="true">
                <defs>
                    <pattern id="dub-grid-password" width="32" height="32" x="50%" y="-1" patternUnits="userSpaceOnUse">
                        <path d="M.5 32V.5H32" fill="none" />
                    </pattern>
                </defs>
                <rect width="100%" height="100%" stroke-width="0" fill="url(#dub-grid-password)" />
            </svg>
        </div>

        {{-- Brand Header --}}
        <a href="{{ url('/') }}" class="mb-7 inline-flex items-center gap-2.5 text-lg font-bold tracking-tight text-slate-900 transition-opacity hover:opacity-80 dark:text-white">
            <span class="flex size-8 items-center justify-center rounded-xl bg-slate-900 text-white shadow-xs dark:bg-white dark:text-slate-950">
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" />
                </svg>
            </span>
            <span>{{ config('app.name') }}</span>
        </a>

        {{-- Main Card --}}
        <div class="w-full max-w-md rounded-3xl border border-slate-200/90 bg-white/90 p-7 shadow-2xl shadow-slate-900/6 backdrop-blur-xl dark:border-dark-800 dark:bg-dark-900/90 dark:shadow-black/50 sm:p-9">
            {{-- Icon & Title --}}
            <div class="text-center">
                <div class="mx-auto mb-5 flex size-14 items-center justify-center rounded-2xl border border-slate-200/80 bg-linear-to-b from-white to-slate-50 text-slate-800 shadow-xs dark:border-dark-700 dark:from-dark-800 dark:to-dark-900 dark:text-slate-200">
                    <svg class="size-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                    </svg>
                </div>

                <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">
                    Password Required
                </h1>
                <p class="mt-2 text-sm leading-relaxed text-slate-500 dark:text-slate-400">
                    This link is protected. Enter the password below to unlock and continue to your destination.
                </p>
            </div>

            {{-- Destination Pill --}}
            <div class="mt-6 rounded-2xl border border-slate-200/80 bg-slate-50/80 px-4 py-3 text-center dark:border-dark-800 dark:bg-dark-950/70">
                <span class="block text-[11px] font-medium uppercase tracking-wider text-slate-400 dark:text-slate-500">
                    Protected Destination
                </span>
                <div class="mt-1 inline-flex max-w-full items-center gap-1.5 font-mono text-xs font-semibold text-blue-600 dark:text-emerald-400 sm:text-sm">
                    <svg class="size-3.5 shrink-0 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                    </svg>
                    <code class="truncate">{{ $url->short_url }}</code>
                </div>
            </div>

            {{-- Error Alert --}}
            @if ($errors->any())
                <div class="mt-5 rounded-2xl border border-rose-200 bg-rose-50/90 p-3.5 text-xs text-rose-700 dark:border-rose-500/30 dark:bg-rose-500/10 dark:text-rose-300" role="alert">
                    <div class="flex items-start gap-2.5">
                        <svg class="mt-0.5 size-4 shrink-0 text-rose-500 dark:text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                        </svg>
                        <ul class="space-y-1 font-medium">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            {{-- Password Form --}}
            <form method="post" action="{{ route('link.password.validate', $url) }}" class="mt-6 space-y-4" x-data="{ show: false }">
                @csrf
                <div>
                    <label for="password" class="mb-1.5 block text-xs font-semibold text-slate-700 dark:text-slate-300">
                        Password
                    </label>
                    <div class="relative">
                        <input
                            id="password"
                            x-bind:type="show ? 'text' : 'password'"
                            name="password"
                            required
                            autofocus
                            placeholder="Enter link password..."
                            class="h-12 w-full rounded-xl border border-slate-200 bg-white pl-4 pr-11 text-sm text-slate-900 placeholder-slate-400 shadow-2xs transition-all focus:border-slate-900 focus:outline-none focus:ring-4 focus:ring-slate-900/10 dark:border-dark-700 dark:bg-dark-950 dark:text-white dark:placeholder-slate-500 dark:focus:border-slate-400 dark:focus:ring-white/10"
                        >
                        <button
                            type="button"
                            x-on:click="show = !show"
                            class="absolute inset-y-0 right-0 flex cursor-pointer items-center pr-3.5 text-slate-400 transition-colors hover:text-slate-600 dark:text-slate-500 dark:hover:text-slate-300"
                            aria-label="Toggle password visibility"
                        >
                            <svg x-show="!show" class="size-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <svg x-show="show" x-cloak class="size-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>
                        </button>
                    </div>
                </div>

                <button
                    type="submit"
                    class="group inline-flex h-12 w-full cursor-pointer items-center justify-center gap-2 rounded-xl bg-slate-900 px-5 text-sm font-semibold text-white shadow-md shadow-slate-900/15 transition-all hover:bg-slate-800 active:scale-[0.99] dark:bg-white dark:text-slate-950 dark:shadow-none dark:hover:bg-slate-100"
                >
                    <svg class="size-4 transition-transform duration-200 group-hover:scale-110" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5V6.75a4.5 4.5 0 119 0v3.75M3.75 21.75h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H3.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                    </svg>
                    <span>Unlock Link</span>
                </button>
            </form>
        </div>
    </div>

    @livewireScripts
</body>
</html>

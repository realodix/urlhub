@extends('layouts.general')

@section('title', 'Sign in to ' . config('app.name'))
@section('css_class', 'auth')

@section('content')
<div class="relative flex min-h-screen flex-col items-center justify-center bg-[#FAFAFA] px-4 py-12 sm:px-6 lg:px-8 dark:bg-neutral-950">
    <!-- Subtle radial background accent -->
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(#e5e7eb_1px,transparent_1px)] bg-size-[16px_16px] mask-[radial-gradient(ellipse_50%_50%_at_50%_50%,#000_70%,transparent_100%)] dark:bg-[radial-gradient(#262626_1px,transparent_1px)]"></div>

    <div class="relative z-10 w-full max-w-100">
        <!-- Card Container -->
        <div class="overflow-hidden rounded-2xl border border-gray-200/80 bg-white shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:border-neutral-800 dark:bg-neutral-900">

            <!-- Header Section -->
            <div class="flex flex-col items-center justify-center border-b border-gray-100 bg-white px-6 pb-6 pt-8 text-center dark:border-neutral-800 dark:bg-neutral-900">
                <h1 class="text-xl font-semibold tracking-tight text-gray-900 dark:text-white">
                    Sign in to {{ config('app.name') }}
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-neutral-400">
                    Start managing your links and analytics
                </p>
            </div>

            <!-- Form Body -->
            <div class="bg-gray-50/50 px-6 py-7 sm:px-8 dark:bg-neutral-900/50">
                @if(session()->has('login_error'))
                    <div class="mb-5 flex items-center gap-2.5 rounded-lg border border-red-200 bg-red-50 px-3.5 py-2.5 text-sm text-red-600 dark:border-red-900/50 dark:bg-red-950/50 dark:text-red-400" role="alert">
                        <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                        </svg>
                        <span>{{ session()->get('login_error') }}</span>
                    </div>
                    {{ session()->forget('login_error') }}
                @endif

                <form method="POST" action="{{ route('login') }}" aria-label="Login" class="space-y-4">
                    @csrf
                    <div>
                        <label for="identity" class="block text-sm font-medium text-gray-700 dark:text-neutral-300">
                            Email or Username
                        </label>
                        <input name="identity" type="text" required autofocus value="{{ old('identity') }}"
                            id="identity"
                            class="mt-1.5 block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2 text-sm text-gray-900 placeholder-gray-400 shadow-xs transition focus:border-black focus:outline-none focus:ring-2 focus:ring-black/10 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white dark:placeholder-neutral-500 dark:focus:border-white dark:focus:ring-white/10"
                        >
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-neutral-300">
                            Password
                        </label>
                        <input type="password" name="password" required autocomplete="current-password" placeholder="••••••••"
                            id="password"
                            class="mt-1.5 block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2 text-sm text-gray-900 placeholder-gray-400 shadow-xs transition focus:border-black focus:outline-none focus:ring-2 focus:ring-black/10 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white dark:placeholder-neutral-500 dark:focus:border-white dark:focus:ring-white/10"
                        >

                        <div class="flex items-center justify-end mt-4 mb-4">
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-xs font-medium text-gray-500 transition hover:text-gray-900 dark:text-neutral-400 dark:hover:text-white">
                                Forgot password?
                            </a>
                        @endif
                        </div>
                    </div>

                    <button
                        type="submit"
                        class="mt-2 flex w-full items-center justify-center rounded-lg border border-black bg-black px-4 py-2.5 text-sm font-medium text-white shadow-xs transition hover:bg-neutral-800 focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 dark:border-white dark:bg-white dark:text-black dark:hover:bg-neutral-200"
                    >
                        Sign in
                    </button>
                </form>
            </div>
        </div>

        <!-- Bottom Link -->
        @if (Route::has('register') && settings()->public_registration)
            <p class="mt-6 text-center text-sm text-gray-500 dark:text-neutral-400">
                Don't have an account?
                <a href="{{ route('register') }}" class="font-medium text-gray-900 underline-offset-4 hover:underline dark:text-white">
                    Sign up
                </a>
            </p>
        @endif
    </div>
</div>
@endsection

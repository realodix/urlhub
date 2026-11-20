@extends('layouts.general')

@section('title', 'Create your ' . config('app.name') . ' account')
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
                    Create your {{ config('app.name') }} account
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-neutral-400">
                    Get started for free. No credit card required.
                </p>
            </div>

            <!-- Form Body -->
            <div class="bg-gray-50/50 px-6 py-7 sm:px-8 dark:bg-neutral-900/50">
                @if (settings()->public_registration === false)
                    <div class="rounded-lg border border-gray-200 bg-white p-4 text-center dark:border-neutral-800 dark:bg-neutral-900">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Registration Closed</p>
                        <p class="mt-1 text-xs text-gray-500 dark:text-neutral-400">
                            Sorry, we are closed for registrations at this time.
                        </p>
                    </div>
                @else
                    <form method="POST" action="{{ route('register') }}" aria-label="Register" class="space-y-4">
                        @csrf
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-neutral-300">
                                Username
                            </label>
                            <input name="name" type="text" required autofocus value="{{ old('name') }}"
                                id="name"
                                class="mt-1.5 block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2 text-sm text-gray-900 placeholder-gray-400 shadow-xs transition focus:border-black focus:outline-none focus:ring-2 focus:ring-black/10 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white dark:placeholder-neutral-500 dark:focus:border-white dark:focus:ring-white/10"
                            >
                            @if ($errors->has('name'))
                                <p class="mt-1.5 text-xs font-medium text-red-600 dark:text-red-400">{{ $errors->first('name') }}</p>
                            @endif
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-neutral-300">
                                Email
                            </label>
                            <input type="email" name="email" required value="{{ old('email') }}"
                                id="email"
                                class="mt-1.5 block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2 text-sm text-gray-900 placeholder-gray-400 shadow-xs transition focus:border-black focus:outline-none focus:ring-2 focus:ring-black/10 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white dark:placeholder-neutral-500 dark:focus:border-white dark:focus:ring-white/10"
                            >
                            @if ($errors->has('email'))
                                <p class="mt-1.5 text-xs font-medium text-red-600 dark:text-red-400">{{ $errors->first('email') }}</p>
                            @endif
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-neutral-300">
                                Password
                            </label>
                            <input type="password" name="password" required autocomplete="new-password" placeholder="••••••••"
                                id="password"
                                class="mt-1.5 block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2 text-sm text-gray-900 placeholder-gray-400 shadow-xs transition focus:border-black focus:outline-none focus:ring-2 focus:ring-black/10 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white dark:placeholder-neutral-500 dark:focus:border-white dark:focus:ring-white/10"
                            >
                            @if ($errors->has('password'))
                                <p class="mt-1.5 text-xs font-medium text-red-600 dark:text-red-400">{{ $errors->first('password') }}</p>
                            @endif
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-neutral-300">
                                Confirm Password
                            </label>
                            <input type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••"
                                id="password_confirmation"
                                class="mt-1.5 block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2 text-sm text-gray-900 placeholder-gray-400 shadow-xs transition focus:border-black focus:outline-none focus:ring-2 focus:ring-black/10 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white dark:placeholder-neutral-500 dark:focus:border-white dark:focus:ring-white/10"
                            >
                        </div>

                        <button
                            type="submit"
                            class="mt-2 flex w-full items-center justify-center rounded-lg border border-black bg-black px-4 py-2.5 text-sm font-medium text-white shadow-xs transition hover:bg-neutral-800 focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 dark:border-white dark:bg-white dark:text-black dark:hover:bg-neutral-200"
                        >
                            Create Account
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Bottom Link -->
        @if (Route::has('login'))
            <p class="mt-6 text-center text-sm text-gray-500 dark:text-neutral-400">
                Already have an account?
                <a href="{{ route('login') }}" class="font-medium text-gray-900 underline-offset-4 hover:underline dark:text-white">
                    Sign in
                </a>
            </p>
        @endif
    </div>
</div>
@endsection

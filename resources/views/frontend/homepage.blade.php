@extends('layouts.frontend')

@section('css_class', 'frontend home')

@section('content')
<div class="pt-16 sm:pt-28">
    @if (!auth()->check() && !settings()->anyone_can_shorten)
        <div class="flex flex-wrap md:justify-center">
            <div class="w-full md:w-8/12 font-thin text-5xl text-slate-600 text-center welcome-msg">
                {{ __('Please login to shorten URLs') }}</div>
        </div>
    @else
        <div class="flex flex-wrap md:justify-center">
            <span class="mx-auto max-w-md md:max-w-3xl
                font-bold text-center text-gray-700 dark:text-dark-300 md:text-4xl xl:text-5xl text-3xl"
            >
                Give every <span class="text-blue-500">link</span> the <span class="hero__emphasizing dark:text-indigo-300">superpowers</span> it deserves
            </span>
        </div>

        <div class="flex flex-wrap justify-center mt-12 md:mt-16 px-4 lg:px-0">
            <div class="w-full max-w-4xl">
                <form method="post" action="{{ route('link.create') }}" class="mb-4 mt-12">
                @csrf
                    <div class="mt-1 text-center">
                        <input name="long_url" required value="{{ old('long_url') }}" placeholder="{{ __('Shorten your link') }}"
                            class="w-full -mb-px md:mb-0 md:w-4/6 px-2 md:px-4 h-12 sm:h-14
                                text-xl outline-none rounded-t-md md:rounded-l-md md:rounded-r-none
                                border border-border-200 focus:border-primary-600
                                dark:bg-dark-900 dark:border-dark-700 dark:focus:border-dark-600
                                {{-- tailwindcss/forms --}}
                                border-border-300 focus:ring-inherit">
                        <button type="submit" class="w-full md:w-1/6 h-12 sm:h-14 align-top rounded-t-none rounded-b md:rounded-l-none md:rounded-r-md duration-300
                            text-lg text-white bg-primary-600 hover:bg-primary-600/90 focus:bg-primary-700
                            dark:text-primary-600 dark:bg-dark-950 dark:focus:bg-dark-800 dark:border dark:border-dark-600 dark:hover:border-primary-600">
                            {{ __('Shorten') }}
                        </button>
                    </div>

                    <br>

                    <div class="custom-url sm:mt-8">
                        <b>{{ __('Custom URL (optional)') }}</b>
                        <span class="block mb-4 font-light dark:text-dark-400">
                            {{ __('Replace clunky URLs with meaningful short links that get more clicks.') }}</span>
                        <div class="inline text-2xl">
                            {{ request()->getHttpHost() }}/ @livewire('validation.validate-custom-keyword')
                        </div>
                    </div>
                </form>

                <div class="mt-8">@include('partials/messages')</div>
            </div>
        </div>
    @endif
</div>
@endsection

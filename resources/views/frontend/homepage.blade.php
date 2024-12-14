@extends('layouts.frontend')

@section('css_class', 'frontend home')

@section('content')
<div class="pt-16 sm:pt-28">
    @if (!auth()->check() and !config('urlhub.public_site'))
        <div class="flex flex-wrap md:justify-center">
            <div class="w-full md:w-8/12 font-thin text-5xl text-slate-600 text-center welcome-msg">
                {{ __('Please login to shorten URLs') }}</div>
        </div>
        <div class="flex flex-wrap md:justify-center mt-12">
            <div class="w-full md:w-7/12">
                @include('partials/messages')</div>
        </div>
    @else
        <div class="flex flex-wrap md:justify-center">
            <h1 class="mx-auto max-w-md md:max-w-3xl relative z-10
                font-bold text-center md:text-4xl xl:text-5xl text-3xl !leading-tight"
            >
                Simple URL shortener <br>
                <span class="font-thin text-black">for individuals &amp; businesses.</span>
            </h1>
        </div>

        <div class="flex flex-wrap justify-center mt-12 px-4 lg:px-0">
            <div class="w-full max-w-4xl">
                <form method="post" action="{{ route('su_create') }}" class="mb-4 mt-12" id="formUrl">
                @csrf
                    <div class="mt-1 text-center">
                        <input name="long_url" value="{{ old('long_url') }}" placeholder="{{ __('Shorten your link') }}"
                            class="w-full md:w-4/6 px-2 md:px-4 h-12 sm:h-14
                                text-xl outline-none
                                border border-border-uh-border-color focus:border-green-600
                                rounded-t-md md:rounded-l-md md:rounded-r-none
                                {{-- tailwindcss/forms --}}
                                border-slate-300 focus:ring-inherit">
                        <button type="submit" id="actProcess"
                            class="w-full md:w-1/6 h-12 sm:h-14 align-top rounded-t-none rounded-b md:rounded-l-none md:rounded-r-md
                                text-lg text-white bg-emerald-600 hover:bg-emerald-700 focus:bg-emerald-700"
                        >
                            {{ __('Shorten') }}
                        </button>
                    </div>

                    <br>

                    <div class="custom-url sm:mt-8">
                        <b>{{ __('Custom URL (optional)') }}</b>
                        <span class="block mb-4 font-light">
                            {{ __('Replace clunky URLs with meaningful short links that get more clicks.') }}</span>
                        <div class="inline text-2xl">
                            {{ request()->getHttpHost() }}/ @livewire('validation.validate-custom-keyword')
                        </div>
                    </div>
                </form>

                @include('partials/messages')
            </div>
        </div>
    @endif
</div>
@endsection

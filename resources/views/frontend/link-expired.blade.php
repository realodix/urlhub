@extends('layouts.frontend')

@section('css_class', 'frontend home')
@section('content')
<div class="flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="card rounded-2xl! relative mx-auto mt-4 w-full max-w-5xl overflow-hidden p-6 text-center sm:p-10 sm:px-0">
        <div class="relative mx-auto flex w-full max-w-md flex-col items-center">
            <x-icon-link-expired class="w-32! h-32! text-orange-500 animate-pulse" />

            <h1 class="font-display mt-10 text-center text-4xl font-medium text-gray-900 dark:text-dark-200 sm:text-5xl sm:leading-[1.15]">
                Expired link
            </h1>

            <p class="mt-6 text-orange-700 dark:text-orange-400">
                {{ urlDisplay($url->short_url, scheme: false) }}
            </p>

            <p class="mt-8 text-gray-700 dark:text-dark-300 sm:text-xl">
                @if ($url->expired_notes)
                    {{ $url->expired_notes }}
                @else
                    This link has expired. Please contact the owner of this link to get a new one.
                @endif
            </p>
        </div>
    </div>
</div>
@endsection

@extends('layouts.frontend')

@section('css_class', 'frontend home')

@section('content')
<div class="flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="card !rounded-3xl max-w-3xl w-full space-y-8 p-12 relative overflow-hidden">
        <div>
            <div class="flex justify-center">
                <x-icon-link-expired class="!w-32 !h-32 text-orange-500 animate-pulse" />
            </div>
            <h2 class="mt-6 text-center text-4xl font-extrabold text-gray-800 dark:text-dark-400 drop-shadow-md">
                Expired link
            </h2>
        </div>

        <div class="text-center">
            <code class="block break-all text-orange-700 font-mono text-sm mt-3">
                {{ $url->short_url }}
            </code>

            <p class="text-lg text-gray-700 dark:text-dark-300 leading-relaxed mt-4">
                This link has expired. Please contact the owner of this link to get a new one.
            </p>
        </div>
    </div>
</div>
@endsection

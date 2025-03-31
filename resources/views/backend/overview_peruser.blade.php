@extends('layouts.backend')

@section('title', __('Overview'))
@section('content')
<div class="page_about container-alt max-w-4xl">
    <div class="card card-fluid overflow-hidden p-8">
        <div class="mb-8">
            <h2 class="text-2xl dark:text-dark-400 tracking-tight">Visited URLs</h2>
            <p class="mt-2 text-md text-gray-600 dark:text-dark-400">A list of your short URLs with the highest number of visits.</p>
        </div>

        <div>
            @php
                $topUrls = \App\Models\Url::getTopUrlsByVisits($user);
            @endphp

            @forelse ($topUrls as $index => $url)
                <div class="flex items-center border-b border-gray-200 py-1 last:border-b-0">
                    <div class="size-8 rounded-full bg-indigo-500 text-white font-bold text-lg flex items-center justify-center mr-4">
                        {{ $index + 1 }}
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-center mb-2">
                            <div>
                                <a href="{{ $url->short_url }}" target="_blank" class="text-blue-600 dark:text-emerald-400 hover:underline font-medium">
                                    {{ $url->keyword }}
                                </a>
                            </div>
                            <span class="text-sm font-medium text-blue-600 dark:text-emerald-400">
                                {{ $url->visits_count }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <a href="{{ route('link.edit', $url) }}" class="text-gray-600 dark:text-dark-400 hover:underline text-sm mt-1">
                                {{ Str::limit($url->destination, 70) }}
                            </a>
                            <span class="text-xs text-gray-400">
                                {{ $url->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center">No URLs found.</p>
            @endforelse
        </div>
    </div>

    <br>

    <div class="card card-fluid overflow-hidden p-8">
        <div class="mb-8">
            <h2 class="text-2xl dark:text-dark-400 tracking-tight">Referrers</h2>
            <p class="mt-2 text-md text-gray-600 dark:text-dark-400">The most common sources of traffic to your short URLs.</p>
        </div>

        <div>
            @php
                $topReferrers = \App\Models\Visit::getTopReferrersForAuthUser($user);
            @endphp

            @forelse ($topReferrers as $index => $referrerData)
                <div class="flex items-center border-b border-gray-200 py-1 last:border-b-0">
                    <div class="size-8 rounded-full bg-indigo-500 text-white font-bold text-lg flex items-center justify-center mr-4">
                        {{ $index + 1 }}
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-center mb-2">
                            <div>
                                @if($referrerData->referer)
                                    <a href="{{ $referrerData->referer }}" target="_blank" rel="noopener noreferrer" class="text-blue-600 dark:text-emerald-400 hover:underline font-medium">
                                        {{ Str::limit($referrerData->referer, 50) }}
                                    </a>
                                @else
                                    <span class="dark:text-dark-400">
                                        Direct / Unknown
                                    </span>
                                @endif
                            </div>
                            <span class="text-sm font-medium text-blue-600 dark:text-emerald-400">
                                {{ number_format($referrerData->total) }}
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center">No referrer data available.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection

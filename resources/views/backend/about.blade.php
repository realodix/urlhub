@use('App\Services\KeyGeneratorService')

@extends('layouts.backend')

@section('title', __('About System'))
@section('content')
<main class="page_about max-w-4xl">
    <x-about.env-check />

    <br>

    @php
        $urlCount = n_abb($url->count());
        $visitCount = n_abb($visit->count());
        $userUrlCount = n_abb($url->userUrlCount());
        $userLinkVisitCount = n_abb($visit->userLinkVisitCount());
        $guestUrlCount = n_abb($url->guestUserUrlCount());
        $guestUserLinkVisitCount = n_abb($visit->guestUserLinkVisitCount());
    @endphp

    <div class="card-default">
        <div class="card_header__sub_header">Links</div>
        @php
            $urlCount = n_abb($url->count());
            $visitCount = n_abb($visit->count());
            $userUrlCount = n_abb($url->userUrlCount());
            $userLinkVisitCount = n_abb($visit->userLinkVisitCount());
            $guestUrlCount = n_abb($url->guestUserUrlCount());
            $guestUserLinkVisitCount = n_abb($visit->guestUserLinkVisitCount());
        @endphp

        <div class="mt-4 mb-6 px-2 md:px-0">
            <dl class="grid grid-cols-1 gap-2.5 sm:gap-3 md:gap-5 md:grid-cols-2 lg:grid-cols-3">
                <div class="bg-neutral-50 border border-border-200 flex items-start px-4 space-x-2 overflow-hidden py-3 text-opacity-0 transition transform rounded-md md:space-x-3">
                    <div>
                        <dt class="-mt-0 text-sm font-medium text-gray-600 md:mt-1">
                            Total
                        </dt>
                        <dd class="-mt-1 font-normal text-gray-900 md:mt-1 md:text-xl">
                            {{ $urlCount }} ({{ $visitCount }} visits)
                        </dd>
                    </div>
                </div>
                <div class="bg-neutral-50 border border-border-200 flex items-start px-4 space-x-2 overflow-hidden py-3 text-opacity-0 transition transform rounded-md md:space-x-3">
                    <div>
                        <dt class="-mt-0 text-sm font-medium text-gray-600 md:mt-1">
                            User
                        </dt>
                        <dd class="-mt-1 font-normal text-gray-900 md:mt-1 md:text-xl">
                            {{ $userUrlCount }} ({{ $userLinkVisitCount }} visits)
                        </dd>
                    </div>
                </div>
                <div class="bg-neutral-50 border border-border-200 flex items-start px-4 space-x-2 overflow-hidden py-3 text-opacity-0 transition transform rounded-md md:space-x-3">
                    <div>
                        <dt class="-mt-0 text-sm font-medium text-gray-600 md:mt-1">
                            Guest
                        </dt>
                        <dd class="-mt-1 font-normal text-gray-900 md:mt-1 md:text-xl">
                            {{ $guestUrlCount }} ({{ $guestUserLinkVisitCount }} visits)
                        </dd>
                    </div>
                </div>
            </dl>
        </div>

        <div class="card_header__sub_header">Users</div>
        <div class="mt-4 mb-6 px-2 md:px-0">
            <dl class="grid grid-cols-1 gap-2.5 sm:gap-3 md:gap-5 md:grid-cols-2 lg:grid-cols-3">
                <div class="bg-neutral-50 border border-border-200 flex items-start px-4 space-x-2 overflow-hidden py-3 text-opacity-0 transition transform rounded-md md:space-x-3">
                    <div>
                        <dt class="-mt-0 text-sm font-medium text-gray-600 md:mt-1">
                            User
                        </dt>
                        <dd class="-mt-1 font-normal text-gray-900 md:mt-1 md:text-xl">
                            {{ n_abb($user->count()) }}
                        </dd>
                    </div>
                </div>
                <div class="bg-neutral-50 border border-border-200 flex items-start px-4 space-x-2 overflow-hidden py-3 text-opacity-0 transition transform rounded-md md:space-x-3">
                    <div>
                        <dt class="-mt-0 text-sm font-medium text-gray-600 md:mt-1">
                            Guest
                        </dt>
                        <dd class="-mt-1 font-normal text-gray-900 md:mt-1 md:text-xl">
                            {{ n_abb($user->totalGuestUsers()) }}
                        </dd>
                    </div>
                </div>
            </dl>
        </div>

        <div class="card_header__sub_header">Random String</div>
        <div class="font-light text-sm">Random String Generation for Shortened URLs.</div>
        <div class="mt-4 mb-6 px-2 md:px-0">
            <dl class="grid grid-cols-1 md:grid-flow-col md:auto-cols-auto gap-2.5 sm:gap-3">
                <div class="bg-neutral-50 border border-border-200 flex items-start px-4 space-x-2 overflow-hidden py-3 text-opacity-0 transition transform rounded-md md:space-x-3">
                    <div>
                        <dt class="-mt-0 text-sm font-medium text-gray-600 md:mt-1">
                            Potential Output
                        </dt>
                        <dd class="-mt-1 font-normal text-gray-900 md:mt-1 md:text-xl">
                            @if ($keyGenerator->possibleOutput() === PHP_INT_MAX)
                                (<code>PHP_INT_MAX</code>) {{ number_format(PHP_INT_MAX) }}
                            @else
                                @php
                                    $number = strlen(KeyGeneratorService::ALPHABET);
                                    $powNumber = config('urlhub.keyword_length');
                                    $result = number_format($keyGenerator->possibleOutput());
                                @endphp

                                ( {{ $number }}<sup>{{ $powNumber }}</sup> ) {{ $result }}
                            @endif
                        </dd>
                    </div>
                </div>
                <div class="bg-neutral-50 border border-border-200 flex items-start px-4 space-x-2 overflow-hidden py-3 text-opacity-0 transition transform rounded-md md:space-x-3">
                    <div>
                        <dt class="-mt-0 text-sm font-medium text-gray-600 md:mt-1 md:w-64">
                            Generated Count
                        </dt>
                        <dd class="-mt-1 font-normal text-gray-900 md:mt-1 md:text-xl md:w-64">
                            {{ number_format($keyGenerator->totalKey()) }}
                        </dd>
                    </div>
                </div>
            </dl>
        </div>
    </div>

    <br>

    @php
        $redirectCacheMaxAge = config('urlhub.redirect_cache_max_age');
        $domainBlacklist = collect(config('urlhub.domain_blacklist'))
            ->sort()->toArray();
        $reservedActiveKeyList = $keyGenerator->reservedActiveKeyword()->toArray();
        $reservedKeyword = $keyGenerator->reservedKeyword();
    @endphp
    <div class="card-default config">
        <div class="card_header">{{ __('Configuration') }}</div>

        <div class="card_header__sub_header">Shortened Links</div>
        <dl>
            <dt><code>keyword_length</code></dt>
            <dd>{{ config('urlhub.keyword_length') }} characters</dd>

            <dt><code>custom_keyword_min_length</code></dt>
            <dd>{{ config('urlhub.custom_keyword_min_length') }} characters</dd>

            <dt><code>custom_keyword_max_length</code></dt>
            <dd>{{ config('urlhub.custom_keyword_max_length') }} characters</dd>

            <dt class="mt-2">
                <code>domain_blacklist</code>
                <div class="font-light text-sm">This is a list of domain names that are not allowed to be shortened.</div>
            </dt>
            <dd class="mt-2">
                <div class="bg-gray-50 p-2 border border-border-300 rounded text-sm">
                    @if (!empty($domainBlacklist))
                        <code>{{ implode(", ", $domainBlacklist) }}</code>
                    @else
                        <code>None</code>
                    @endif
                </div>
            </dd>

            <dt class="mt-2 mb-2">
                <code>reserved_keyword</code>
                <div class="font-light text-sm">
                    Reserved keywords are strings that cannot be used as a shortened URL keyword. The route list and folder/file names in the public folder are also included in this list.
                </div>
            </dt>
            <dd class="mt-2 mb-2">
                <div class="bg-gray-50 p-2 border border-border-300 rounded text-sm">
                    <code class="text-slate-500">// {{ $reservedKeyword->count() }} Strings</code> <br>
                    <code>{{ $reservedKeyword->implode(', ') }}</code>

                    @if (!empty($reservedActiveKeyList))
                        <br><br>
                        <code class="text-red-400">// Unfortunately the list below is already used </code> <br>
                        <code class="text-red-400">// as shortened URL keyword</code> <br>
                        <code>

                        @foreach ($reservedActiveKeyList as $reservedActiveKey)
                            <a href="{{ route('link_detail', $reservedActiveKey) }}"
                                target="_blank"
                                class="underline decoration-dotted">{{ $reservedActiveKey }}</a>,
                        @endforeach
                        </code>
                    @endif
                </div>
            </dd>

            <dt><code>web_title</code></dt>
            <dd>
                <code class="config-value-bool">{{ var_export(config('urlhub.web_title')) }}</code>
            </dd>

            <dt><code>redirect_status_code</code></dt>
            <dd>{{ config('urlhub.redirect_status_code') }}</dd>

            <dt><code>redirect_cache_max_age</code></dt>
            <dd>{{ $redirectCacheMaxAge.' '.str()->plural('second', $redirectCacheMaxAge) }}</dd>

            <dt><code>track_bot_visits</code></dt>
            <dd>
                <code class="config-value-bool">{{ var_export(config('urlhub.track_bot_visits')) }}</code>
            </dd>
        </dl>

        <div class="card_header__sub_header">Guest / Unregistered Users</div>
        <dl>
            <dt>Allow create short links</dt>
            <dd>
                <code class="config-value-bool">{{ var_export(config('urlhub.public_site')) }}</code>
            </dd>

            <dt>Allow sign up</dt>
            <dd>
                <code class="config-value-bool">{{ var_export(config('urlhub.registration')) }}</code>
            </dd>
        </dl>
    </div>
</main>
@endsection

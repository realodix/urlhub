@use('App\Services\KeyGeneratorService')

@extends('layouts.backend')

@section('title', __('About System'))
@section('content')
<main class="page_about max-w-4xl">
    <x-about.env class="mb-6" />

    <div class="content">
        <h3>Links</h3>
        @php
            $urlCount = n_abb($url->count());
            $visitCount = n_abb($visit->count());
            $userUrlCount = n_abb($url->userUrlCount());
            $userLinkVisitCount = n_abb($visit->userLinkVisitCount());
            $guestUrlCount = n_abb($url->guestUserUrlCount());
            $guestUserLinkVisitCount = n_abb($visit->guestUserLinkVisitCount());
        @endphp

        <div class="mt-4 mb-6 px-0">
            <dl class="grid grid-cols-1 gap-2.5 sm:gap-3 md:gap-5 md:grid-cols-2 lg:grid-cols-3">
                <div class="card !bg-gray-50 !rounded px-4 py-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-600 md:mt-1">
                            Total
                        </dt>
                        <dd class="-mt-1 font-normal text-gray-900 md:mt-1 md:text-xl">
                            {{ $urlCount }} ({{ $visitCount }} visits)
                        </dd>
                    </div>
                </div>
                <div class="card !bg-gray-50 !rounded px-4 py-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-600 md:mt-1">
                            User
                        </dt>
                        <dd class="-mt-1 font-normal text-gray-900 md:mt-1 md:text-xl">
                            {{ $userUrlCount }} ({{ $userLinkVisitCount }} visits)
                        </dd>
                    </div>
                </div>
                <div class="card !bg-gray-50 !rounded px-4 py-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-600 md:mt-1">
                            Guest
                        </dt>
                        <dd class="-mt-1 font-normal text-gray-900 md:mt-1 md:text-xl">
                            {{ $guestUrlCount }} ({{ $guestUserLinkVisitCount }} visits)
                        </dd>
                    </div>
                </div>
            </dl>
        </div>

        <h3>Users</h3>
        <div class="mt-4 mb-6 px-0">
            <dl class="grid grid-cols-1 gap-2.5 sm:gap-3 md:gap-5 md:grid-cols-2 lg:grid-cols-3">
                <div class="card !bg-gray-50 !rounded px-4 py-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-600 md:mt-1">
                            User
                        </dt>
                        <dd class="-mt-1 font-normal text-gray-900 md:mt-1 md:text-xl">
                            {{ n_abb($user->count()) }}
                        </dd>
                    </div>
                </div>
                <div class="card !bg-gray-50 !rounded px-4 py-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-600 md:mt-1">
                            Guest
                        </dt>
                        <dd class="-mt-1 font-normal text-gray-900 md:mt-1 md:text-xl">
                            {{ n_abb($user->totalGuestUsers()) }}
                        </dd>
                    </div>
                </div>
            </dl>
        </div>

        <h3>Random String</h3>
        <p class="font-light text-sm">Random String Generation for Shortened URLs.</p>
        <div class="mt-4 mb-6 px-0">
            <dl class="grid grid-cols-1 md:grid-flow-col md:auto-cols-auto gap-2.5 sm:gap-3">
                <div class="card !bg-gray-50 !rounded px-4 py-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-600 md:mt-1">
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
                <div class="card !bg-gray-50 !rounded px-4 py-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-600 md:mt-1 md:w-64">
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
    <div class="content config">
        <h1>{{ __('Configuration') }}</h1>

        <h3>Shortened Links</h3>
        <dl>
            <dt><code>keyword_length</code></dt>
            <dd>{{ config('urlhub.keyword_length') }} characters</dd>

            <dt><code>custom_keyword_min_length</code></dt>
            <dd>{{ config('urlhub.custom_keyword_min_length') }} characters</dd>

            <dt><code>custom_keyword_max_length</code></dt>
            <dd>{{ config('urlhub.custom_keyword_max_length') }} characters</dd>

            <dt class="mt-2">
                <code>domain_blacklist</code>
                <p class="font-light text-sm">This is a list of domain names that are not allowed to be shortened.</p>
            </dt>
            <dd class="mt-2">
                <div class="card !bg-gray-50 !rounded px-3 py-2 text-sm">
                    @if (!empty($domainBlacklist))
                        <code>{{ implode(", ", $domainBlacklist) }}</code>
                    @else
                        <code>None</code>
                    @endif
                </div>
            </dd>

            <dt class="mt-2 mb-2">
                <code>reserved_keyword</code>
                <p class="font-light text-sm">
                    Reserved keywords are strings that cannot be used as a shortened URL keyword. The route list and folder/file names in the public folder are also included in this list.
                </p>
            </dt>
            <dd class="mt-2 mb-2">
                <div class="card !bg-gray-50 !rounded px-3 py-2 text-sm">
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

        <h3>Guest / Unregistered Users</h3>
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

@use('App\Services\KeyGeneratorService')

@extends('layouts.backend')

@section('title', __('About System'))
@section('content')
<main class="page_about max-w-4xl">
    <div class="flex flex-wrap gap-4 mb-4 justify-end">
        <div class="bg-uh-bg-1 p-4 sm:rounded-lg w-full md:w-2/6
            border-y border-uh-border-color sm:border-none sm:shadow-md"
        >
            <div class="flex flex-row space-x-4 items-center">
                <div>
                    <p class="text-[#4f5b93] text-sm font-medium leading-4">PHP</p>
                    <p class="text-2xl font-bold text-gray-600 inline-flex items-center space-x-2">
                        {{ phpversion() }}
                    </p>
                </div>
            </div>
        </div>
        <div class="bg-uh-bg-1 p-4 sm:rounded-lg w-full md:w-2/6
            border-y border-uh-border-color sm:border-none sm:shadow-md"
        >
            <div class="flex flex-row space-x-4 items-center">
                <div>
                    <p class="text-[#ff2d20] text-sm font-medium leading-4">Laravel</p>
                    <p class="text-2xl font-bold text-gray-600 inline-flex items-center space-x-2">
                        {{ app()->version() }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <br>

    <div class="common-card-style">
        <div class="card_header__sub_header">Links</div>
        <dl>
            @php
                $urlCount = n_abb($url->count());
                $visitCount = n_abb($visit->count());
                $userUrlCount = n_abb($url->userUrlCount());
                $userLinkVisitCount = n_abb($visit->userLinkVisitCount());
                $guestUrlCount = n_abb($url->guestUserUrlCount());
                $guestUserLinkVisitCount = n_abb($visit->guestUserLinkVisitCount());
            @endphp
            <dt>Total</dt>
            <dd>{{ $urlCount }} ({{ $visitCount }} visits)</dd>

            <dt>User</dt>
            <dd>{{ $userUrlCount }} ({{ $userLinkVisitCount }} visits)</dd>

            <dt>Guest</dt>
            <dd>{{ $guestUrlCount }} ({{ $guestUserLinkVisitCount }} visits)</dd>
        </dl>

        <div class="card_header__sub_header">Users</div>
        <dl>
            <dt>User</dt>
            <dd>{{ n_abb($user->count()) }}</dd>

            <dt>Guest</dt>
            <dd>{{ n_abb($user->totalGuestUsers()) }}</dd>
        </dl>

        <div class="card_header__sub_header">Random String</div>
        <dl>
            <dt>Possible Output</dt>
            <dd>
                @php
                    $number = strlen(KeyGeneratorService::ALPHABET);
                    $powNumber = config('urlhub.keyword_length');
                    $result = number_format($keyGenerator->possibleOutput());
                @endphp

                @if ($keyGenerator->possibleOutput() === PHP_INT_MAX)
                    (<code>PHP_INT_MAX</code>) {{ number_format(PHP_INT_MAX) }}
                @else
                    ( {{ $number }}<sup>{{ $powNumber }}</sup> ) {{ $result }}
                @endif
            </dd>

            <dt>Generated</dt>
            <dd>{{ number_format($keyGenerator->totalKey()) }}</dd>
        </dl>
    </div>

    <br>

    @php
        $redirectCacheMaxAge = config('urlhub.redirect_cache_max_age');
        $domainBlacklist = collect(config('urlhub.domain_blacklist'))
            ->sort()->toArray();
        $reservedActiveKeyList = $keyGenerator->reservedActiveKeyword()->toArray();
        $reservedKeyword = $keyGenerator->reservedKeyword();
    @endphp
    <div class="common-card-style">
        <div class="card_header">{{ __('Configuration') }}</div>

        <div class="card_header__sub_header">Shortened Links</div>
        <dl>
            <dt><code>keyword_length</code></dt>
            <dd>{{ config('urlhub.keyword_length') }} characters</dd>

            <dt><code>custom_keyword_min_length</code></dt>
            <dd>{{ config('urlhub.custom_keyword_min_length') }} characters</dd>

            <dt><code>custom_keyword_max_length</code></dt>
            <dd>{{ config('urlhub.custom_keyword_max_length') }} characters</dd>

            <dt class="mt-2"><code>domain_blacklist</code></dt>
            <dd class="mt-2">
                <div class="bg-gray-50 p-2 border border-gray-300 rounded text-sm">
                    @if (!empty($domainBlacklist))
                        <code>{{ implode(", ", $domainBlacklist) }}</code>
                    @else
                        <code>None</code>
                    @endif
                </div>
            </dd>

            <dt class="mt-2 mb-2">Reserved Keywords</dt>
            <dd class="mt-2 mb-2">
                <div class="bg-gray-50 p-2 border border-gray-300 rounded text-sm">
                    <code class="text-gray-500">// {{ $reservedKeyword->count() }} Strings</code> <br>
                    <code>{{ $reservedKeyword->implode(', ') }}</code>

                    @if (!empty($reservedActiveKeyList))
                        <br><br>
                        <code class="text-red-400">// Unfortunately the list below is already used </code> <br>
                        <code class="text-red-400">// as shortened URL keyword</code> <br>
                        <code>

                        @foreach ($reservedActiveKeyList as $reservedActiveKey)
                            <a href="{{ route('su_detail', $reservedActiveKey) }}"
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

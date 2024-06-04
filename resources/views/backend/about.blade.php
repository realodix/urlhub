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
                            {{phpversion()}}
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
                            {{app()->version()}}
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
                    $tUrl = numberAbbreviate($url->count());
                    $tUrlVisit = numberAbbreviate($visit->count());
                    $userUrlCount = numberAbbreviate($url->where('user_id', '!=' , null)->count());
                    $userClickCount = numberAbbreviate($visit->userClickCount());
                    $guestUrlCount = numberAbbreviate($url->numberOfUrlFromGuests());
                    $guestUserClickCount = numberAbbreviate($visit->guestUserUrlVisitCount());
                @endphp
                <dt>Total</dt>
                <dd>{{$tUrl}} ({{$tUrlVisit}} visits)</dd>

                <dt>From Users</dt>
                <dd>{{$userUrlCount}} ({{$userClickCount}} visits)</dd>

                <dt>From Unregistered Users</dt>
                <dd>{{$guestUrlCount}} ({{$guestUserClickCount}} visits)</dd>
            </dl>

            <div class="card_header__sub_header">Users</div>
            <dl>
                <dt>Registered</dt>
                <dd>{{numberAbbreviate($user->count())}}</dd>

                <dt>Unregistered</dt>
                <dd>{{numberAbbreviate($user->totalGuestUsers())}}</dd>
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
                    ( {{$number}}<sup>{{$powNumber}}</sup> ) {{$result}}
                </dd>

                <dt>Generated</dt>
                <dd>{{number_format($keyGenerator->totalKey())}}</dd>
            </dl>
        </div>

        <br>

        <div class="common-card-style">
            <div class="card_header">{{__('Configuration')}}</div>

            <div class="card_header__sub_header">Shortened Links</div>
            <dl>
                @php
                    $hashLength = config('urlhub.keyword_length');
                    $customKeywordMinLength = config('urlhub.custom_keyword_min_length');
                    $customKeywordMaxLength = config('urlhub.custom_keyword_max_length');
                    $redirectCacheMaxAge = config('urlhub.redirect_cache_max_age');
                @endphp
                <dt><code>keyword_length</code></dt>
                <dd>{{$hashLength.' '.str('character')->plural($hashLength)}}</dd>

                <dt><code>custom_keyword_min_length</code></dt>
                <dd>{{$customKeywordMinLength.' '.str('character')->plural($customKeywordMinLength)}}</dd>

                <dt><code>custom_keyword_max_length</code></dt>
                <dd>{{$customKeywordMaxLength.' '.str('character')->plural($customKeywordMaxLength)}}</dd>

                @php
                    $domainBlacklist = collect(config('urlhub.domain_blacklist'))
                        ->sort()->toArray();
                @endphp
                <dt class="mt-2"><code>domain_blacklist</code></dt>
                <dd class="mt-2">
                    <div class="bg-gray-50 p-2 border border-gray-300 rounded text-sm">
                        @if (! empty($domainBlacklist))
                            <code>{{implode(", ", $domainBlacklist)}}</code>
                        @else
                            <code>None</code>
                        @endif
                    </div>
                </dd>

                @php
                    $reservedKey = collect(config('urlhub.reserved_keyword'))
                        ->sort()->toArray();
                @endphp
                <dt class="mt-2 mb-2">Reserved Keywords</dt>
                <dd class="mt-2 mb-2">
                    <div class="bg-gray-50 p-2 border border-gray-300 rounded text-sm">
                        <p><b>Config</b></p>
                        <code>{{implode(", ", $reservedKey)}}</code>

                        <br> <br>

                        <p><b>Registered routes</b></p>
                        <code>{{implode(", ", \App\Helpers\Helper::routeList())}}</code>

                        <br> <br>

                        <p><b>Public Folder</b></p>
                        <code>{{implode(", ", \App\Helpers\Helper::publicPathList())}}</code>
                    </div>
                </dd>

                <dt><code>web_title</code></dt>
                <dd>
                    <code class="config-value-bool">{{var_export(config('urlhub.web_title'))}}</code>
                </dd>

                <dt><code>redirect_status_code</code></dt>
                <dd>{{config('urlhub.redirect_status_code')}}</dd>

                <dt><code>redirect_cache_max_age</code></dt>
                <dd>{{$redirectCacheMaxAge.' '.str('second')->plural($redirectCacheMaxAge)}}</dd>

                <dt><code>track_bot_visits</code></dt>
                <dd>
                    <code class="config-value-bool">{{var_export(config('urlhub.track_bot_visits'))}}</code>
                </dd>
            </dl>

            <div class="card_header__sub_header">Guest / Unregistered Users</div>
            <dl>
                <dt>Create short links</dt>
                <dd>
                    <code class="config-value-bool">{{var_export(config('urlhub.public_site'))}}</code>
                </dd>

                <dt>Sign up</dt>
                <dd>
                    <code class="config-value-bool">{{var_export(config('urlhub.registration'))}}</code>
                </dd>
            </dl>

            <div class="card_header__sub_header">QRCode</div>
            <dl>
                <dt>Enabled</dt>
                <dd>
                    <code class="config-value-bool">{{var_export(config('urlhub.qrcode'))}}</code>
                </dd>

                <dt>Size</dt>
                <dd>{{config('urlhub.qrcode_size')}} px</dd>

                <dt>Margin</dt>
                <dd>{{config('urlhub.qrcode_margin')}} px</dd>

                <dt>Format</dt>
                <dd>{{config('urlhub.qrcode_format')}}</dd>

                <dt>Error correction levels</dt>
                <dd>{{config('urlhub.qrcode_error_correction')}}</dd>

                <dt>Round block</dt>
                <dd>
                    <code class="config-value-bool">{{var_export(config('urlhub.qrcode_round_block_size'))}}</code>
                </dd>
            </dl>
        </div>
    </main>
@endsection

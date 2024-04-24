@extends('layouts.backend')

@section('title', __('About System'))
@section('content')
    <main class="page_about">
        <div class="text-3xl md:text-4xl text-center">
            <span class="text-[#ff2d20]">@svg('icon-brand-laravel') {{app()->version()}}</span> -
            <span class="text-[#4f5b93]">@svg('icon-brand-php') {{phpversion()}}</span>
        </div>

        <br>

        <div class="common-card-style">
            <div class="card_header__sub_header">Links</div>
            <dl>
                @php
                    $tUrl = numberAbbreviate($url->count());
                    $tUrlVisit = numberAbbreviate($visit->count());
                    $nUrlFromUser = numberAbbreviate($url->where('user_id', '!=' , null)->count());
                    $nUrlVisitFromUser = numberAbbreviate($visit->count() - $url->numberOfClickFromGuest());
                    $nUrlFromGuest = numberAbbreviate($url->numberOfUrlFromGuests());
                    $nUrlVisitFromGuest = numberAbbreviate($url->numberOfClickFromGuest());
                @endphp
                <dt>Total</dt>
                <dd>{{$tUrl}} ({{$tUrlVisit}} visits)</dd>

                <dt>From Users</dt>
                <dd>{{$nUrlFromUser}} ({{$nUrlVisitFromUser}} visits)</dd>

                <dt>From Unregistered Users</dt>
                <dd>{{$nUrlFromGuest}} ({{$nUrlVisitFromGuest}} visits)</dd>
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
                <dd>( 62<sup>{{config('urlhub.keyword_length')}}</sup> ) {{number_format($keyGenerator->possibleOutput())}}</dd>

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

                <dt><code>web_title</code></dt>
                <dd>{{var_export(config('urlhub.web_title'))}}</dd>

                <dt><code>redirect_status_code</code></dt>
                <dd>{{config('urlhub.redirect_status_code')}}</dd>

                <dt><code>redirect_cache_max_age</code></dt>
                <dd>{{$redirectCacheMaxAge.' '.str('second')->plural($redirectCacheMaxAge)}}</dd>

                <dt><code>track_bot_visits</code></dt>
                <dd>{{var_export(config('urlhub.track_bot_visits'))}}</dd>
            </dl>

            <div class="card_header__sub_header">Guest / Unregistered Users</div>
            <dl>
                <dt>Create short links</dt>
                <dd>{{var_export(config('urlhub.public_site'))}}</dd>

                <dt>Sign up</dt>
                <dd>{{var_export(config('urlhub.registration'))}}</dd>
            </dl>

            <div class="card_header__sub_header">QRCode</div>
            <dl>
                <dt>Enabled</dt>
                <dd>{{var_export(config('urlhub.qrcode'))}}</dd>

                <dt>Size</dt>
                <dd>{{config('urlhub.qrcode_size')}} px</dd>

                <dt>Margin</dt>
                <dd>{{config('urlhub.qrcode_margin')}} px</dd>

                <dt>Format</dt>
                <dd>{{config('urlhub.qrcode_format')}}</dd>

                <dt>Error correction levels</dt>
                <dd>{{config('urlhub.qrcode_error_correction')}}</dd>

                <dt>Round block</dt>
                <dd>{{var_export(config('urlhub.qrcode_round_block_size'))}}</dd>
            </dl>
        </div>
    </main>
@endsection

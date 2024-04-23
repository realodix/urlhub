@extends('layouts.backend')

@section('title', __('About System'))
@section('content')
    <main>
        <div class="text-3xl md:text-4xl text-center">
            <span class="text-[#ff2d20]">@svg('icon-brand-laravel') {{app()->version()}}</span> -
            <span class="text-[#4f5b93]">@svg('icon-brand-php') {{phpversion()}}</span>
        </div>

        <br>

        <div class="common-card-style">
            <div class="card_header__sub_header">Links</div>
            <table>
                <tbody>
                    <tr>
                        <td class="w-72">Total</td>
                        <td>{{numberAbbreviate($url->count())}} ({{numberAbbreviate($visit->count())}} visits)</td>
                    </tr>
                    <tr>
                        <td>From Users</td>
                        <td>{{numberAbbreviate($url->where('user_id', '!=' , null)->count())}} ({{numberAbbreviate($visit->count() - $url->numberOfClickFromGuest())}} visits)</td>
                    </tr>
                    <tr>
                        <td>From Unregistered Users</td>
                        <td>{{numberAbbreviate($url->numberOfUrlFromGuests())}} ({{numberAbbreviate($url->numberOfClickFromGuest())}} visits)</td>
                    </tr>
                </tbody>
            </table>

            <div class="card_header__sub_header">Users</div>
            <table>
                <tbody>
                    <tr>
                        <td class="w-72">Registered</td>
                        <td>{{numberAbbreviate($user->count())}}</td>
                    </tr>
                    <tr>
                        <td>Unregistered</td>
                        <td>{{numberAbbreviate($user->totalGuestUsers())}}</td>
                    </tr>
                </tbody>
            </table>

            <div class="card_header__sub_header">Random String</div>
            <table>
                <tbody>
                    <tr>
                        <td class="w-72">Possible Output</td>
                        <td>( 62<sup>{{config('urlhub.keyword_length')}}</sup> ) {{number_format($keyGenerator->possibleOutput())}}</td>
                    </tr>
                    <tr>
                        <td>Generated</td>
                        <td>{{number_format($keyGenerator->totalKey())}}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <br>

        <div class="common-card-style">
            <div class="card_header">{{__('Configuration')}}</div>

            <div class="card_header__sub_header">Shortened Links</div>
            <table>
                <tbody>
                    @php
                        $hashLength = config('urlhub.keyword_length');
                        $customKeywordMinLength = config('urlhub.custom_keyword_min_length');
                        $customKeywordMaxLength = config('urlhub.custom_keyword_max_length');
                        $redirectCacheMaxAge = config('urlhub.redirect_cache_max_age');
                    @endphp
                    <tr>
                        <td class="w-72"><code>keyword_length</code></td>
                        <td>{{$hashLength.' '.str('character')->plural($hashLength)}}</td>
                    </tr>
                    <tr>
                        <td class="w-72"><code>custom_keyword_min_length</code></td>
                        <td>{{$customKeywordMinLength.' '.str('character')->plural($customKeywordMinLength)}}</td>
                    </tr>
                    <tr>
                        <td class="w-72"><code>custom_keyword_max_length</code></td>
                        <td>{{$customKeywordMaxLength.' '.str('character')->plural($customKeywordMaxLength)}}</td>
                    </tr>
                    <tr>
                        <td><code>web_title</code></td>
                        <td>{{var_export(config('urlhub.web_title'))}}</td>
                    </tr>
                    <tr>
                        <td><code>redirect_status_code</code></td>
                        <td>{{config('urlhub.redirect_status_code')}}</td>
                    </tr>
                    <tr>
                        <td><code>redirect_cache_max_age</code></td>
                        <td>{{$redirectCacheMaxAge.' '.str('second')->plural($redirectCacheMaxAge)}}</td>
                    </tr>
                    <tr>
                        <td><code>track_bot_visits</code></td>
                        <td>{{var_export(config('urlhub.track_bot_visits'))}}</td>
                    </tr>
                </tbody>
            </table>

            <div class="card_header__sub_header">Guest / Unregistered Users</div>
            <table>
                <tbody>
                    <tr>
                        <td class="w-72">Create short links</td>
                        <td>{{var_export(config('urlhub.public_site'))}}</td>
                    </tr>
                    <tr>
                        <td>Sign up</td>
                        <td>{{var_export(config('urlhub.registration'))}}</td>
                    </tr>
                </tbody>
            </table>

            <div class="card_header__sub_header">QRCode</div>
            <table>
                <tbody>
                    <tr>
                        <td class="w-72">Enable</td>
                        <td>{{var_export(config('urlhub.qrcode'))}}</td>
                    </tr>
                    <tr>
                        <td>Size</td>
                        <td>{{config('urlhub.qrcode_size')}} px</td>
                    </tr>
                    <tr>
                        <td>Margin</td>
                        <td>{{config('urlhub.qrcode_margin')}} px</td>
                    </tr>
                    <tr>
                        <td>Format</td>
                        <td>{{config('urlhub.qrcode_format')}}</td>
                    </tr>
                    <tr>
                        <td>Error correction levels</td>
                        <td>{{config('urlhub.qrcode_error_correction')}}</td>
                    </tr>
                    <tr>
                        <td>Round block</td>
                        <td>{{var_export(config('urlhub.qrcode_round_block_size'))}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>
@endsection

@extends('layouts.backend')

@section('title', __('About System'))

@section('content')
    <main>
        <div class="common-card-style p-4">
            <h4>Links</h4>
            <table>
                <tbody>
                    <tr>
                        <td class="w-72">Total</td>
                        <td>{{$url->count()}}</td>
                    </tr>
                    <tr>
                        <td>From Registered Users</td>
                        <td>{{$url->whereUserId(auth()->id())->count()}}</td>
                    </tr>
                    <tr>
                        <td>From Unregistered Users</td>
                        <td>{{$url->numberOfUrlsByGuests()}}</td>
                    </tr>
                </tbody>
            </table>

            <br>

            <h4>Users</h4>
            <table>
                <tbody>
                    <tr>
                        <td class="w-72">Registered</td>
                        <td>{{$user->count()}}</td>
                    </tr>
                    <tr>
                        <td>Unregistered</td>
                        <td>{{$user->totalGuestUsers()}}</td>
                    </tr>
                </tbody>
            </table>

            <br>

            <h4>Random String</h4>
            <table>
                <tbody>
                    <tr>
                        <td class="w-72">Possible Output</td>
                        <td>{{$keyGeneratorService->maxCapacity()}}</td>
                    </tr>
                    <tr>
                        <td>Generated</td>
                        <td>{{$keyGeneratorService->usedCapacity()}}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <br>

        <div class="common-card-style p-4">
            <div class="flex mb-8">
                <div class="w-1/2">
                    <span class="text-2xl text-uh-1">
                        {{__('Configuration')}}
                    </span>
                </div>
            </div>

            <h4>Shortened Links</h4>
            <table>
                <tbody>
                    @php
                        $hashLength = config('urlhub.hash_length');
                        $redirectCacheMaxAge = config('urlhub.redirect_cache_max_age');
                    @endphp
                    <tr>
                        <td class="w-72">Random string length</td>
                        <td>{{$hashLength.' '.str('character')->plural($hashLength)}}</td>
                    </tr>
                    <tr>
                        <td>web_title</td>
                        <td>{{var_export(config('urlhub.web_title'))}}</td>
                    </tr>
                    <tr>
                        <td>redirect_status_code</td>
                        <td>{{config('urlhub.redirect_status_code')}}</td>
                    </tr>
                    <tr>
                        <td>redirect_cache_max_age</td>
                        <td>{{$redirectCacheMaxAge.' '.str('second')->plural($redirectCacheMaxAge)}}</td>
                    </tr>
                    <tr>
                        <td>track_bot_visits</td>
                        <td>{{var_export(config('urlhub.track_bot_visits'))}}</td>
                    </tr>
                </tbody>
            </table>

            <br>

            <h4>Guest / Unregistered Users</h4>
            <table>
                <tbody>
                    <tr>
                        <td class="w-72">Anyone can shorten the link</td>
                        <td>{{var_export(config('urlhub.public_site'))}}</td>
                    </tr>
                    <tr>
                        <td>Anyone can sign up</td>
                        <td>{{var_export(config('urlhub.registration'))}}</td>
                    </tr>
                </tbody>
            </table>

            <br>

            <h4>QRCode</h4>
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

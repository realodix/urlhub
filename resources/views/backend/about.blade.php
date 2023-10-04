@extends('layouts.backend')

@section('title', __('About System'))

@section('content')
    <main>
        <div class="common-card-style p-4">
            <h3>Short Links</h3>
            <table>
                <tbody>
                    <tr>
                        <td class="w-60">Total</td>
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

            <h3>Users</h3>
            <table>
                <tbody>
                    <tr>
                        <td class="w-60">Registered</td>
                        <td>{{$user->count()}}</td>
                    </tr>
                    <tr>
                        <td>Unregistered</td>
                        <td>{{$user->totalGuestUsers()}}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <br>

        <div class="common-card-style p-4">
            <div class="flex mb-8">
                <div class="w-1/2">
                    <span class="text-2xl text-uh-1">
                        {{__('Config')}}
                    </span>
                </div>
            </div>

            <h4>Random String</h4>
            <table>
                <tbody>
                    <tr>
                        <td class="w-60">Length</td>
                        <td>{{config('urlhub.hash_length')}}</td>
                    </tr>
                    <tr>
                        <td>Possible Output</td>
                        <td>{{$keyGeneratorService->maxCapacity()}}</td>
                    </tr>
                    <tr>
                        <td>Generated</td>
                        <td>{{$keyGeneratorService->usedCapacity()}}</td>
                    </tr>
                </tbody>
            </table>

            <br>

            <h4>Unregistered Users Access</h4>
            <table>
                <tbody>
                    <tr>
                        <td class="w-60">Anyone can shorten the link</td>
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
                        <td class="w-60">Enable</td>
                        <td>{{var_export(config('urlhub.qrcode'))}}</td>
                    </tr>
                    <tr>
                        <td>Size</td>
                        <td>{{config('urlhub.qrcode_size')}} PX</td>
                    </tr>
                    <tr>
                        <td>Margin</td>
                        <td>{{config('urlhub.qrcode_margin')}}</td>
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

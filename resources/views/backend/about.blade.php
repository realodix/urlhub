@extends('layouts.backend')

@section('title', __('About System'))

@section('content')
    <main>
        <div class="common-card-style p-4">
            <div class="flex mb-8">
                <div class="w-1/2">
                    <span class="text-2xl text-uh-1">
                        {{__('About System')}}
                    </span>
                </div>
            </div>

            <h3>Short URLs</h3>
            <table>
                <tbody>
                    <tr>
                        <td class="w-60">Characters</td>
                        <td>{{config('urlhub.hash_char')}}</td>
                    </tr>
                    <tr>
                        <td>Length</td>
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
    </main>
@endsection

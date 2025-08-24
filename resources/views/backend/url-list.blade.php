@extends('layouts.backend')

@section('title', 'URLs List')
@section('content')
<div class="container-alt max-w-340">
    <div class="w-full md:max-w-md">
        @include('partials.messages')
    </div>

    <div class="content-container card card-master">
        <div class="content-header grid-cols-1! md:grid-cols-2!">
            <p class="text-2xl">List of All URLs</p>

            <div class="md:flex justify-end mt-4 md:mt-0">
                <a href="{{ route('dboard.allurl.u-user', \App\Models\User::GUEST_NAME) }}" title="Shortened links by Guests" class="btn btn-secondary">
                    By Guest
                </a>

                <a href="{{ route('dboard.links.restricted') }}" title="Restricted links" class="btn btn-secondary ml-2">
                    Restricted
                </a>
            </div>
        </div>

        @livewire('table.UrlTable')
    </div>
</div>
@endsection

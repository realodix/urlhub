@extends('layouts.backend')

@section('title', __('URLs List'))

@section('content')
<main>
    <div class="common-card-style">
        <div class="card_header__v2">
            <div class="w-1/2">
                <span class="text-2xl text-black">
                    {{ __('List of All URLs') }}
                </span>
            </div>
            <div class="w-1/2 text-right">
                <a href="{{ route('dashboard.allurl.u-guest') }}" title="{{ __('Shortened long links by Guest') }}" class="btn btn-primary">
                    {{ __('By Guest') }}
                </a>
            </div>
        </div>

        @include('partials/messages')

        @livewire('table.url-list-table')
    </div>
</main>
@endsection

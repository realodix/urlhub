@extends('layouts.backend')

@section('title', __('URLs List'))

@section('content')
<main>
    <div class="card-default">
        <div class="card_header__v2">
            <div class="w-1/2">
                <span class="text-2xl text-slate-800">
                    {{ __('List of All URLs') }}
                </span>
            </div>
            <div class="w-1/2 text-right">
                <a href="{{ route('dboard.allurl.u-guest') }}" title="{{ __('Shortened long links by Guest') }}" class="btn btn-primary">
                    {{ __('By Guest') }}
                </a>
            </div>
        </div>

        @include('partials/messages')

        @livewire('table.url-list-table')
    </div>
</main>
@endsection

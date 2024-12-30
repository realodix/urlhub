@extends('layouts.backend')

@section('title', __('Links').'  >  Guests')

@section('content')
<main>
    <div class="card-default">
        <div class="card_header__v2">
            <div class="w-1/2">
                <span class="text-2xl text-slate-800">
                    {{ __('Links created by Guests') }}
                </span>
            </div>
        </div>

        @include('partials/messages')

        @livewire('table.url-list-of-guest-table')
    </div>
</main>
@endsection

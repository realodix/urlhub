@extends('layouts.backend')

@section('title', __('Links').'  >  Guests')

@section('content')
<main>
    <div class="common-card-style">
        <div class="card_header__v2">
            <div class="w-1/2">
                <span class="text-2xl text-uh-1">
                    {{ __('Links created by Guests') }}
                </span>
            </div>
        </div>

        @include('partials/messages')

        @livewire('table.url-list-of-guest_table')
    </div>
</main>
@endsection

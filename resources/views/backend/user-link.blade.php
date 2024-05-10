@extends('layouts.backend')

@section('title', __('All URLs List > Guests'))

@section('content')
    <main>
        <div class="common-card-style">
            <div class="card_header__v2">
                <div class="w-1/2">
                    <span class="text-2xl text-uh-1">
                        {{__('All URLs Created By Guests')}}
                    </span>
                </div>
            </div>

            @include('partials/messages')

            @livewire('table.user-link-table')
        </div>
    </main>
@endsection

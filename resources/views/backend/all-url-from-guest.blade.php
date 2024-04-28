@extends('layouts.backend')

@section('title', __('All URLs From Guest'))

@section('content')
    <main>
        <div class="common-card-style">
            <div class="card_header__v2">
                <div class="w-1/2">
                    <span class="text-2xl text-uh-1">
                        {{__('All URLs From Guest')}}
                    </span>
                </div>
            </div>

            @include('partials/messages')

            @livewire('table.all_url_from_guest_table')
        </div>
    </main>
@endsection

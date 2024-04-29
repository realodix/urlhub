@extends('layouts.backend')

@section('title', __('All URLs'))

@section('content')
    <main>
        <div class="common-card-style">
            <div class="card_header__v2">
                <div class="w-1/2">
                    <span class="text-2xl text-uh-1">
                        {{__('All URLs')}}
                    </span>
                </div>
                <div class="w-1/2 text-right">
                    <a href="{{ route('dashboard.allurl-from-guest') }}" title="{{__('Shortened long links by Guest')}}" class="btn btn-secondary">
                        {{__('By Guest')}}
                    </a>
                </div>
            </div>

            @include('partials/messages')

            @livewire('table.all-url-table')
        </div>
    </main>
@endsection

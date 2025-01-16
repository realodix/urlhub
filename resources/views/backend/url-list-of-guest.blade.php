@extends('layouts.backend')

@section('title', __('Links').'  >  Guests')

@section('content')
<main>
    <div class="content">
        <div class="content-header">
            <p class="text-2xl">{{ __('Links created by Guests') }}</p>
        </div>

        @include('partials/messages')

        @livewire('table.url-list-of-guest-table')
    </div>
</main>
@endsection

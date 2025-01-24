@extends('layouts.backend')

@section('title', __('Links').'  >  Guests')

@section('content')
<main>
    <div class="w-full md:max-w-md">
        @include('partials/messages')
    </div>

    <div class="content">
        <div class="content-header">
            <p class="text-2xl">{{ __('Links created by Guests') }}</p>
        </div>

        @livewire('table.url-list-of-guest-table')
    </div>
</main>
@endsection

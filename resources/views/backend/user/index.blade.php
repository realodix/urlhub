@extends('layouts.backend')

@section('title', __('All Users'))

@section('content')
<main>
    <div class="common-card-style">
        <div class="card_header__v2">{{ __('All Users') }}</div>

        @livewire('table.user-table')
    </div>
</main>
@endsection

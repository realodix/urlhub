@extends('layouts.backend')

@section('title', __('All Users'))

@section('content')
<main>
    <div class="content">
        <div class="content-header">
            <p class="text-2xl">{{ __('All Users') }}</p>
        </div>

        @livewire('table.user-table')
    </div>
</main>
@endsection

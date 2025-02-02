@extends('layouts.backend')

@section('title', __('All Users'))

@section('content')
<div class="container">
    <div class="content-container card card-fluid">
        <div class="content-header">
            <p class="text-2xl">{{ __('All Users') }}</p>
        </div>

        @livewire('table.user-table')
    </div>
</div>
@endsection

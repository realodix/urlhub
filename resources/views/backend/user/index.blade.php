@extends('layouts.backend')

@section('title', __('All Users'))

@section('content')
<div class="container-alt max-w-340">
    <div class="w-full md:max-w-md">
        @include('partials/messages')
    </div>

    <div class="content-container card card-fluid">
        <div class="content-header">
            <p class="text-2xl">{{ __('All Users') }}</p>
            <div class="flex justify-end">
                <a href="{{ route('user.new') }}" title="{{ __('Add New User') }}" class="btn btn-primary">
                    @svg('icon-add-link', '!h-[1.5em] mr-1')
                    <p class="hidden sm:inline">{{ __('Add New User') }}</p>
                </a>
            </div>
        </div>

        @livewire('table.user-table')
    </div>
</div>
@endsection

@extends('layouts.backend')

@section('title', 'All Users')
@section('content')
<div class="container-alt max-w-340">
    <div class="w-full md:max-w-md">
        @include('partials.messages')
    </div>

    <div class="content-container card card-master">
        <div class="content-header">
            <p class="text-2xl">All Users</p>
            <div class="flex justify-end">
                <a href="{{ route('user.new') }}" title="Add New User" class="btn btn-primary">
                    @svg('icon-user-add', 'mr-1')
                    <p class="hidden sm:inline">Add New User</p>
                </a>
            </div>
        </div>

        @livewire('table.user-table')
    </div>
</div>
@endsection

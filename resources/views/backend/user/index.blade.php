@extends('layouts.backend')

@section('title', __('All Users'))

@section('content')
<main>
    <div class="bg-white p-4 sm:rounded-md shadow">
        <div class="text-2xl text-uh-1 mb-8">
            <span>{{__('All Users')}}</span>
        </div>

        @livewire('user-table')
    </div>
</main>
@endsection

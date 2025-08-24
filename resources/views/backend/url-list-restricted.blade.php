@extends('layouts.backend')

@section('title', 'Restricted Links')
@section('content')
    <div class="container-alt max-w-340">
        <div class="w-full md:max-w-md">
            @include('partials.messages')
        </div>

        <div class="content-container card card-master">
            <div class="content-header"><p class="text-2xl">Restricted Links</p></div>

            @livewire('table.UrlTableByRestricted', ['author' => $author ?? null])
        </div>
    </div>
@endsection

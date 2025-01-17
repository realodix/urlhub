@extends('layouts.backend')

@section('title', __('Links').'  >  '. $authorName)

@section('content')
<main>
    <div class="content">
        <div class="content-header">
            <p class="text-2xl">{{ __('Links created by') }} {{ $authorName }}</p>
        </div>

        @include('partials/messages')

        @livewire('table.url_list_of_users_table', ['user_id' => $authorId])
    </div>
</main>
@endsection

@extends('layouts.backend')

@section('title', __('Links').'  >  '. $authorName)

@section('content')
<main>
    <div class="common-card-style">
        <div class="card_header__v2">
            <div class="w-1/2">
                <span class="text-2xl text-black">
                    {{ __('Links created by') }} {{ $authorName }}
                </span>
            </div>
        </div>

        @include('partials/messages')

        @livewire('table.url_list_of_users_table', ['user_id' => $authorId])
    </div>
</main>
@endsection

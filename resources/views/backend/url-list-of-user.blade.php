@extends('layouts.backend')

@section('title', __('Links').'  >  '. $authorName)

@section('content')
<div class="container-alt max-w-340">
    <div class="content-container card card-fluid">
        <div class="content-header">
            <p class="text-2xl">{{ __('Links created by') }} {{ $authorName }}</p>
        </div>

        @livewire('table.url_list_of_users_table', ['user_id' => $authorId])
    </div>
</div>
@endsection

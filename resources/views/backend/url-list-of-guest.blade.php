@extends('layouts.backend')

@section('title', __('Links').'  >  Guests')

@section('content')
<div class="container-alt max-w-340">
    <div class="w-full md:max-w-md">
        @include('partials.messages')
    </div>

    <div class="content-container card card-master">
        <div class="content-header">
            <p class="text-2xl">{{ __('Links created by Guests') }}</p>
        </div>

        @livewire('table.UrlTableByUser', ['user_id' => \App\Models\Url::GUEST_ID])
    </div>
</div>
@endsection

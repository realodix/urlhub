@extends('layouts.backend')

@section('title', 'Links > '. $author)
@section('content')
<div class="container-alt max-w-340">
    <div class="content-container card card-master">
        <div class="content-header">
            <p class="text-2xl">Links created by {{ $author }}</p>
        </div>

        @livewire('table.UrlTableByUser', ['user_id' => \App\Models\User::findIdByName($author)])
    </div>
</div>
@endsection

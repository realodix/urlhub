@extends('layouts.backend')

@section('title', 'Links > '. $author)
@section('content')
<div class="container-alt max-w-340">
    <div class="overflow-hidden rounded-2xl border border-gray-200/80 bg-white shadow-xs dark:border-dark-800 dark:bg-dark-900">
        <div class="flex flex-wrap items-center justify-between gap-4 border-b border-gray-200/80 bg-gray-50/60 px-6 py-4 dark:border-dark-800 dark:bg-dark-950/40">
            <div>
                <h1 class="text-lg font-semibold tracking-tight text-gray-900 sm:text-xl dark:text-white">
                    Links created by {{ $author }}
                </h1>
            </div>
        </div>

        <div class="p-6">
            @livewire('table.UrlTableByUser', ['user_id' => \App\Models\User::findIdByName($author)])
        </div>
    </div>
</div>
@endsection

@extends('layouts.backend')

@section('title', 'Restricted Links')
@section('content')
    <div class="container-alt max-w-340">
        <div class="w-full md:max-w-md">
            @include('partials.messages')
        </div>

        <div class="overflow-hidden rounded-2xl border border-gray-200/80 bg-white shadow-xs dark:border-dark-800 dark:bg-dark-900">
            <div class="flex flex-wrap items-center justify-between gap-4 border-b border-gray-200/80 bg-gray-50/60 px-6 py-4 dark:border-dark-800 dark:bg-dark-950/40">
                <div>
                    <h1 class="text-lg font-semibold tracking-tight text-gray-900 sm:text-xl dark:text-white">
                        Restricted Links
                    </h1>
                </div>
            </div>

            <div class="p-6">
                @livewire('table.UrlTableByRestricted', ['author' => $author ?? null])
            </div>
        </div>
    </div>
@endsection

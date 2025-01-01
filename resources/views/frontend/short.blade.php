@extends('layouts.frontend')

@section('css_class', 'frontend view_short')
@section('content')
<div class="max-w-7xl mx-auto mb-12">
    <div class="md:w-10/12 mt-6 lg:mt-8 px-4 sm:p-6">
        <div class="text-xl sm:text-2xl lg:text-3xl mb-4">{{ $url->title }}</div>

        <ul class="mb-4">
            <li class="inline-block pr-4 mt-4 lg:mt-0">
                @svg('icon-chart-line-alt')
                <i>
                    <span title="{{ number_format($visitsCount) }}" class="font-bold">
                        {{ n_abb($visitsCount) }}
                    </span>
                </i>
            </li>
            <li class="inline-block pr-4">
                @svg('icon-calendar')
                <i>{{ $url->created_at->toFormattedDateString() }}</i>
            </li>
        </ul>
    </div>

    <div class="card-default flex flex-wrap mt-6 sm:mt-0 px-4 py-5 sm:p-6">
        <div class="w-full md:w-1/4 flex justify-center">
            <img class="qrcode h-fit" src="{{ $qrCode->getDataUri() }}" alt="QR Code">
        </div>
        <div class="w-full md:w-3/4 mt-8 sm:mt-0">
            <div class="text-right pr-6">
                <button id="clipboard_shortlink"
                    class="btn btn-primary btn-square btn-sm mr-6"
                    title="{{ __('Copy the shortened URL to clipboard') }}"
                    data-clipboard-text="{{ $url->short_url }}"
                >
                    @svg('icon-clone')
                </button>

                @auth
                    @if (auth()->user()->hasRole('admin') || (auth()->user()->id === $url->user_id))
                        <a href="{{ route('link.edit', $url) }}" title="{{ __('Edit') }}"
                            class="btn btn-primary btn-square btn-sm mr-6">
                            @svg('icon-edit')
                        </a>
                        <a href="{{ route('link_detail.delete', $url) }}" title="{{ __('Delete') }}"
                            class="btn btn-primary btn-square btn-sm hover:bg-red-100 hover:border-red-200 hover:text-red-800 active:text-red-700">
                            @svg('icon-trash')
                        </a>
                    @endif
                @endauth
            </div>

            <br>

            <span class="text-indigo-700 font-bold text-xl sm:text-2xl">
                <a href="{{ $url->short_url }}" target="_blank" id="copy">
                    {{ urlFormat($url->short_url, scheme: false) }}
                </a>
            </span>

            <div class="mt-2">
                <div class="flex gap-x-2">
                    <div class="hidden md:block">@svg('arrow-turn-right')</div>
                    <div class="break-all max-w-2xl">
                        <a href="{{ $url->destination }}" target="_blank" rel="noopener noreferrer" class="redirect-anchor">
                            {{ $url->destination }}
                        </a>
                    </div>
                </div>
            </div>

            <div class="mt-20">
                @livewire(\App\Livewire\Chart\LinkVisitChart::class, ['model' => $url])
            </div>

        </div>
    </div>
</div>
@endsection

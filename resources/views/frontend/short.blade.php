@extends('layouts.frontend')

@section('css_class', 'frontend view_short')
@section('content')
<div class="max-w-7xl mx-auto mb-12">
    <div class="md:w-10/12 mt-6 lg:mt-8 px-4 sm:p-6">
        <div class="text-xl sm:text-2xl lg:text-3xl md:mb-4">{{ $url->title }}</div>

        <ul class="mb-4">
            @if (auth()->check() && (auth()->user()->id === $url->user_id || auth()->user()->hasRole('admin')))
            <li class="inline-block pr-4 mt-4 lg:mt-0">
                @svg('icon-chart-line-alt')
                <i>
                    <span title="{{ number_format($visitsCount) }}" class="font-bold">
                        {{ n_abb($visitsCount) }}
                    </span>
                </i>
            </li>
            @endif
            <li class="inline-block pr-4">
                @svg('icon-calendar')
                <i>{{ $url->created_at->toFormattedDateString() }}</i>
            </li>
        </ul>
    </div>

    <div class="content flex flex-wrap mt-6 sm:mt-0 px-4 py-5 sm:p-6">
        <div class="w-full md:w-1/4 flex justify-center md:mt-10">
            <img class="qrcode h-fit" src="{{ $qrCode->getDataUri() }}" alt="QR Code">
        </div>
        <div class="w-full md:w-3/4 mt-8 sm:mt-0">
            <div class="text-right pr-6">
                <button id="clipboard_shortlink"
                    title="{{ __('Copy the shortened URL to clipboard') }}"
                    data-clipboard-text="{{ $url->short_url }}"
                    class="btn btn-secondary btn-square btn-sm mr-6"
                >
                    @svg('icon-clone')
                </button>

                @if (auth()->check() && (auth()->user()->id === $url->user_id || auth()->user()->hasRole('admin')))
                    <a href="{{ route('link.edit', $url) }}" title="{{ __('Edit') }}" class="btn btn-secondary btn-square btn-sm mr-6">
                        @svg('icon-edit')
                    </a>
                    <a href="{{ route('link_detail.delete', $url) }}" title="{{ __('Delete') }}"
                        class="btn btn-delete btn-square btn-sm"
                    >
                        @svg('icon-trash')
                    </a>
                @endif
            </div>

            <br>

            <div class="mt-10 sm:mt-0">
                <p class="text-primary-700 font-bold text-xl sm:text-2xl">
                    <a href="{{ $url->short_url }}" target="_blank" id="copy">
                        {{ urlFormat($url->short_url, scheme: false) }}
                    </a>
                </p>

                <div class="flex gap-x-2 mt-2">
                    <div class="hidden md:block dark:text-dark-400">@svg('icon-arrow-turn-right', 'dark:text-dark-400')</div>
                    <p class="break-all max-w-2xl dark:text-dark-400">
                        <a href="{{ $url->destination }}" target="_blank" rel="noopener noreferrer">
                            {{ $url->destination }}
                        </a>
                    </p>
                </div>
            </div>

            <div class="mt-20">
                @if (auth()->check() && (auth()->user()->id === $url->user_id || auth()->user()->hasRole('admin')))
                    @livewire(\App\Livewire\Chart\LinkVisitChart::class, ['model' => $url])
                @else
                    <div class="bg-orange-50 border border-border-200 p-4 text-center
                        dark:bg-transparent dark:border-dark-700 dark:text-dark-400">
                        If this is a link you created from your account, please <a href="{{ route('login') }}" class="text-orange-700 hover:text-orange-500 font-medium">log in</a> to view the statistics for this link.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

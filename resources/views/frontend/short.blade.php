@extends('layouts.frontend')

@section('css_class', 'frontend view_short')

@section('content')
    <div class="max-w-7xl mx-auto mb-12">
        <div class="flex flex-wrap mt-6 lg:mt-8 px-4 sm:p-6">
            <div class="md:w-9/12">
                <div class="text-xl sm:text-2xl lg:text-3xl font-bold mb-4">{!! $url->title !!}</div>

                <ul class="mb-4">
                    <li class="inline-block pr-4">
                        @svg('icon-calendar')
                        <i>{{$url->created_at->toDayDateTimeString()}}</i>
                    </li>
                    <li class="inline-block pr-4 mt-4 lg:mt-0">
                        @svg('icon-bar-chart')
                        <i>
                            <span title="{{number_format($url->clicks)}}" class="font-bold">
                                {{compactNumber($url->clicks)}}
                            </span>
                        </i>
                        {{__('Total engagements')}}
                    </li>
                </ul>
            </div>
        </div>

        <div class="common-card-style flex flex-wrap mt-6 sm:mt-0 px-4 py-5 sm:p-6">
            @if (config('urlhub.qrcode'))
                <div class="w-full md:w-1/4 flex justify-center">
                    <img class="qrcode h-fit" src="{{$qrCode->getDataUri()}}" alt="QR Code">
                </div>
            @endif
            <div class="w-full md:w-3/4 mt-8 sm:mt-0">
                <div @class(['mb-8' => session('msgLinkAlreadyExists')])>
                    @include('partials/messages')
                </div>

                <button title="{{__('Copy the shortened URL to clipboard')}}"
                    data-clipboard-text="{{$url->short_url}}"
                    class="btn-clipboard btn-icon-detail"
                >
                    @svg('icon-clone') {{__('Copy')}}
                </button>

                @auth
                    @if (auth()->user()->hasRole('admin') || (auth()->user()->id === $url->user_id))
                        <button class="btn-clipboard btn-icon-detail">
                            <a href="{{route('dashboard.su_edit', $url->keyword)}}" title="{{__('Edit')}}">
                                @svg('icon-edit') {{__('Edit')}}
                            </a>
                        </button>

                        <button class="btn-clipboard btn-icon-detail hover:text-red-600 active:text-red-700">
                            <a href="{{route('su_delete', $url->getRouteKey())}}" title="{{__('Delete')}}">
                                @svg('icon-trash') {{__('Delete')}}
                            </a>
                        </button>
                    @endif
                @endauth

                <br> <br>

                <span class="font-bold text-indigo-700 text-xl sm:text-2xl">
                    <a href="{{ $url->short_url }}" target="_blank" id="copy">
                        {{urlDisplay($url->short_url, scheme: false)}}
                    </a>
                </span>

                <div class="break-all max-w-2xl mt-2">
                    @svg('arrow-turn-right') <a href="{{ $url->destination }}" target="_blank" rel="noopener noreferrer" class="redirect-anchor">{{ urlDisplay($url->destination, limit: 80) }}</a>
                </div>
            </div>
        </div>
    </div>
@endsection

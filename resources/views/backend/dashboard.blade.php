@extends('layouts.backend')

@section('title', __('Dashboard'))

@section('content')
    <main>
        <div class="common-card-style mb-4 p-4">
            @role('admin')
                <div class="flex flex-wrap">
                    <div class="w-full sm:w-1/4">
                        <span class="text-cyan-600"> @svg('icon-square', 'mr-2') {{__('All')}}</span>
                        <span class="text-teal-600 ml-5"> @svg('icon-square', 'mr-2') {{__('Me')}}</span>
                        <span class="text-orange-600 ml-5"> @svg('icon-square', 'mr-2') {{__('Guests')}}</span>
                    </div>
                    <div class="mt-8 sm:mt-0 text-uh-1 ">
                        <b>@svg('icon-storage', 'mr-1.5') {{__('Free Space')}}:</b>
                        <span class="font-light">{{numberShorten($url->keyRemaining())}} {{__('of')}}
                            {{numberShorten($url->keyCapacity())}} ({{$url->keyRemainingInPercent()}})
                        </span>
                    </div>
                </div>

                <div class="flex flex-wrap sm:mt-8">
                    <div class="w-full sm:w-1/4">
                        <div class="block">
                            <b class="text-uh-1">@svg('icon-link', 'mr-1.5') {{__('URLs')}}:</b>
                            <span class="text-cyan-600">{{numberShorten($url->totalUrl())}}</span> -
                            <span class="text-teal-600">{{numberShorten($url->urlCount(auth()->id()))}}</span> -
                            <span class="text-orange-600">{{numberShorten($url->urlCount())}}</span>
                        </div>
                        <div class="block">
                            <b class="text-uh-1">@svg('icon-bar-chart', 'mr-1.5') {{__('Clicks')}}:</b>
                            <span class="text-cyan-600">{{numberShorten($url->totalClick())}}</span> -
                            <span class="text-teal-600">{{numberShorten($url->clickCount(auth()->id()))}}</span> -
                            <span class="text-orange-600">{{numberShorten($url->clickCount())}}</span>
                        </div>
                    </div>
                    <div class="text-uh-1 w-full sm:w-1/4 mt-4 sm:mt-0">
                        <div class="block">
                            <b>@svg('icon-user', 'mr-1.5') {{__('Users')}}:</b>
                            <span class="font-light">{{numberShorten($user->count())}}ddd</span>
                        </div>
                        <div class="block">
                            <b>@svg('icon-user', 'mr-1.5') {{__('Guests')}}:</b>
                            <span class="font-light">{{numberShorten($user->guestCount())}}</span>
                        </div>
                    </div>
                </div>
            @else
                <div class="flex flex-wrap">
                    <div class="w-full sm:w-1/4">
                        <span class="font-semibold text-md sm:text-2xl">@svg('icon-link', 'mr-1.5') {{__('URLs')}}:</span>
                        <span class="font-light text-lg sm:text-2xl">{{numberShorten($url->urlCount(auth()->id()))}}</span>
                    </div>
                    <div class="w-full sm:w-1/4">
                        <span class="font-semibold text-lg sm:text-2xl">@svg('icon-eye', 'mr-1.5') {{__('Clicks')}}:</span>
                        <span class="font-light text-lg sm:text-2xl">{{numberShorten($url->clickCount(auth()->id()))}}</span>
                    </div>
                </div>
            @endrole
        </div>

        <div class="common-card-style p-4">
            <div class="flex mb-8">
                <div class="w-1/2">
                    <span class="text-2xl text-uh-1">{{__('My URLs')}}</span>
                </div>
                <div class="w-1/2 text-right">
                    <a href="{{ url('./') }}" target="_blank" title="{{__('Add URL')}}" class="btn">
                        @svg('icon-add-link', '!h-[1.5em] mr-1')
                        {{__('Add URL')}}
                    </a>
                </div>
            </div>

            @include('partials/messages')

            @livewire('table.my-url-table')
        </div>
    </main>
@endsection

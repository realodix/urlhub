@extends('layouts.frontend')

@section('css_class', 'frontend view_short')

@section('content')
<div class="max-w-7xl mx-auto mb-12">
  <div class="flex flex-wrap mt-6 lg:mt-12 px-4 sm:p-6">
    <div class="md:w-9/12">

      @include('partials/messages')

      <ul>
        <li class="inline-block pr-4">
          <x-fas-calendar-alt />
          <i>{{$url->created_at->toDayDateTimeString()}}</i>
        </li>
        <li class="inline-block pr-4">
          <x-gmdi-bar-chart />
          <i><span title="{{number_format($url->clicks)}}">{{numberToAmountShort($url->clicks)}}</span></i>
        </li>
        @auth
          @if (Auth::user()->hasRole('admin') || (Auth::user()->id == $url->user_id))
            <li class="inline-block pr-4">
              <a href="{{route('short_url.edit', $url->keyword)}}" title="{{__('Edit')}}"
                class="text-xs text-white bg-gray-500 hover:bg-uh-indigo-600 active:bg-uh-indigo-500 px-2 py-1 rounded-lg shadow-sm"
              >
                <x-fas-edit />
              </a>
            </li>
            <li class="inline-block">
              <a href="{{route('dashboard.delete', $url->getRouteKey())}}" title="{{__('Delete')}}"
                class="text-xs text-white bg-gray-500 hover:bg-red-600 active:bg-uh-indigo-500 px-2 py-1 rounded-lg shadow-sm"
              >
                <x-fas-trash-alt />
              </a>
            </li>
          @endif
        @endauth
      </ul>
      <div class="text-xl sm:text-2xl lg:text-3xl mt-2 font-light">{!! $url->meta_title !!}</div>
    </div>
  </div>

  <div class="flex flex-wrap mt-6 sm:mt-0 px-4 py-5 sm:p-6 bg-white shadow sm:rounded-md">
    <div class="w-full md:w-1/4 flex justify-center">
      <img class="qrcode" src="data:{{$qrCode->getContentType()}};base64,{{$qrCode->generate()}}" alt="QR Code">
    </div>
    <div class="w-full md:w-3/4 mt-8 sm:mt-0">
      <b class="text-indigo-700">{{__('Shortened URL')}}</b>
      <button title="{{__('Copy to clipboard')}}" data-clipboard-text="{{urlDisplay($url->short_url, false)}}"
        class="btn-clipboard ml-4 py-0.5 px-1
          text-xs text-white bg-green-600 hover:bg-green-700 focus:bg-green-600"
      >
        <x-fas-clone /> {{__('Copy')}}
      </button>

      <br>

      <span class="font-light"><a href="{{ $url->short_url }}" target="_blank"
        id="copy">{{urlDisplay($url->short_url, false)}}</a></span>

      <br> <br>

      <b class="text-indigo-700">{{__('Destination URL')}}</b>
      <button title="{{__('Copy to clipboard')}}" data-clipboard-text="{{ $url->long_url }}"
        class="btn-clipboard ml-4 py-0.5 px-1
          text-xs text-white bg-green-600 hover:bg-green-700 focus:bg-green-600"
      >
        <x-fas-clone /> {{__('Copy')}}
      </button>

      <div class="font-light break-all">{{ $url->long_url }}</div>
    </div>
  </div>
</div>
@endsection

@extends('layouts.frontend')

@section('css_class', 'frontend view_short')

@section('content')
<div class="max-w-7xl mx-auto mb-12">
  <div class="flex flex-wrap mt-6 lg:mt-12 px-4 sm:p-6">
    <div class="md:w-9/12">

      @include('partials/messages')

      <ul>
        <li class="inline-block pr-4">
          @svg('fas-calendar-alt')
          <i>{{$url->created_at->toDayDateTimeString()}}</i>
        </li>
        <li class="inline-block pr-4">
          @svg('gmdi-bar-chart')
          <i><span title="{{number_format($url->clicks)}}">{{numberToAmountShort($url->clicks)}}</span></i>
        </li>
        @auth
          @if (Auth::user()->hasRole('admin') || (Auth::user()->id == $url->user_id))
            <li class="inline-block pr-2">
              <a href="{{route('short_url.edit', $url->keyword)}}" title="{{__('Edit')}}"
                class="btn-icon text-xs"
              >
                @svg('fas-edit')
              </a>
            </li>
            <li class="inline-block">
              <a href="{{route('dashboard.delete', $url->getRouteKey())}}" title="{{__('Delete')}}"
                class="btn-icon text-xs hover:text-red-700 active:text-red-600"
              >
                @svg('fas-trash-alt')
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
      <img class="qrcode" src="{{$qrCode->getDataUri()}}" alt="QR Code">
    </div>
    <div class="w-full md:w-3/4 mt-8 sm:mt-0">
      <b class="text-indigo-700">{{__('Shortened URL')}}</b>
      <button title="{{__('Copy the shortened URL to clipboard')}}" data-clipboard-text="{{urlDisplay($url->short_url, false)}}"
        class="btn-clipboard btn-icon text-xs ml-4"
      >
        @svg('fas-clone')
      </button>

      <br>

      <span class="font-light"><a href="{{ $url->short_url }}" target="_blank"
        id="copy">{{urlDisplay($url->short_url, false)}}</a></span>

      <br> <br>

      <b class="text-indigo-700">{{__('Destination URL')}}</b>
      <button title="{{__('Copy the destination URL to clipboard')}}" data-clipboard-text="{{ $url->long_url }}"
        class="btn-clipboard btn-icon text-xs ml-4"
      >
        @svg('fas-clone')
      </button>

      <div class="font-light break-all">{{ $url->long_url }}</div>
    </div>
  </div>
</div>
@endsection

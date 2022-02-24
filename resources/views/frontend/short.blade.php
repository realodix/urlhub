@extends('layouts.frontend')

@section('css_class', 'frontend view_short')

@section('content')
<div class="max-w-7xl mx-auto mb-12">
  <div class="flex flex-wrap mt-6 sm:mt-12 px-4 sm:p-6">
    <div class="md:w-9/12">

      @include('partials/messages')

      <ul>
        <li class="inline-block pr-4">
          <i class="far fa-clock"></i>
          <i>{{$url->created_at->toDayDateTimeString()}}</i>
        </li>
        <li class="inline-block">
          <i class="far fa-eye"></i>
          <i><span title="{{number_format($url->clicks)}}">{{numberToAmountShort($url->clicks)}}</span></i>
        </li>
      </ul>
      <div class="text-4xl font-light">{!! $url->meta_title !!}</div>
    </div>
  </div>

  <div class="flex flex-wrap mt-6 sm:mt-0 px-4 py-5 sm:p-6 bg-white shadow sm:rounded-md">
    <div class="w-full md:w-1/4 flex justify-center">
      <img class="qrcode" src="data:{{$qrCode->getContentType()}};base64,{{$qrCode->generate()}}" alt="QR Code">
    </div>
    <div class="w-full md:w-3/4 mt-8 sm:mt-0">
      <b>@lang('Shortened URL')</b>
      <button title="@lang('Copy to clipboard')" data-clipboard-text="{{urlDisplay($url->short_url, false)}}"
        class="btn-clipboard ml-4 py-0.5 px-1
          text-xs text-indigo-500 hover:text-white hover:bg-indigo-500 focus:bg-indigo-600 border-indigo-500"
      >
        @lang('Copy')
      </button>

      <br>

      <span class="font-light"><a href="{{ $url->short_url }}" target="_blank"
        id="copy">{{urlDisplay($url->short_url, false)}}</a></span>

      <br> <br>

      <b>@lang('Original URL')</b>
      <button title="@lang('Copy to clipboard')" data-clipboard-text="{{ $url->long_url }}"
        class="btn-clipboard ml-4 py-0.5 px-1
          text-xs text-indigo-500 hover:text-white hover:bg-indigo-500 focus:bg-indigo-600 border-indigo-500"
      >
        @lang('Copy')
      </button>

      <div class="font-light break-all">{{ $url->long_url }}</div>
    </div>
  </div>
</div>
@endsection

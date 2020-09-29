@extends('layouts.frontend')

@section('css_class', 'frontend view_short')

@section('content')
<div class="container mb-5">
  <div class="row header mt-5">
    <div class="col-md-9">

      @include('messages')

      <ul class="list-inline">
        <li class="list-inline-item">
          <i class="far fa-clock"></i>
          <i>{{ $url->created_at->toDayDateTimeString() }}</i>
        </li>
        <li class="list-inline-item">
          <i class="far fa-eye"></i>
          <i><span title="{{number_format($url->clicks)}} clicks"
              data-toggle="tooltip">{{numberToAmountShort($url->clicks)}}</span></i>
        </li>
      </ul>
      <div class="title">{!! $url->meta_title !!}</div>
    </div>
  </div>

  <div class="row mt-3">
    <div class="col-lg">
      <div class="row body">
        <div class="col-sm-3">
          <img class="qrcode" src="data:{{$qrCode->getContentType()}};base64,{{$qrCode->generate()}}" alt="QR Code">
        </div>
        <div class="col-sm-9">
          <b>@lang('Short URL')</b> <br>
          <span class="short-url"><a href="{{ $url->short_url }}" target="_blank"
              id="copy">{{ urlDisplay($url->short_url, false) }}</a></span>
          <button class="btn btn-sm btn-outline-success btn-clipboard ml-3"
            data-clipboard-text="{{ urlDisplay($url->short_url, false) }}" title="@lang('Copy to clipboard')"
            data-toggle="tooltip">@lang('Copy')</button>

          <br> <br>

          <b>@lang('Original URL')</b>
          <div class="long-url">{{ $url->long_url }}</div>

          <div class="mt-5" id="jssocials"></div>

          @if (uHub('embed') == true)
          <div class="webInfo mt-3">
            {!! $webInfo !!}
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>


  @if (uHub('guest_show_stat') == true)
    @include('frontend.short_stat')
  @else
    @auth
      @include('frontend.short_stat')
    @endauth
  @endif

</div>
@endsection

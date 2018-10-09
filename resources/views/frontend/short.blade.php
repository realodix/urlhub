@extends('layouts.frontend')

@section('css_class', 'frontend view_short')

@section('content')
<div class="container">
  <div class="row header mt-5">
  <div class="col-md-9">
    @if (session('msgLinkAlreadyExists'))
    <div class="alert alert-success">
      {{ session('msgLinkAlreadyExists') }}
    </div>
    @endif

    <ul class="list-inline">
      <li class="list-inline-item">
        <i class="far fa-clock"></i>
        <i>{{ $created_at }}</i>
      </li>
      <li class="list-inline-item">
        <i class="far fa-eye"></i>
        <i><span title="{{number_format($views)}} views" data-toggle="tooltip">{{readable_int($views)}}</span></i>
      </li>
    </ul>
    <div class="title">{{ $long_url_title }}</div>
  </div>
  </div>

  <div class="row mt-3">
  <div class="col-md-9">
    <div class="row body">
      <div class="col-md">
        <img src="data:{{$qrCodeData}};base64,{{$qrCodebase64}}" alt="QR Code">
      </div>
      <div class="col-md-9">
        <b>@lang('Original URL')</b>
        <div class="long-url"><a href="{{ $long_url }}" target="_blank" title="{{ $long_url }}" data-toggle="tooltip">{{ url_limit($long_url) }}</a></div>

        <br>

        <b>@lang('Short URL')</b> <br>
        <span class="short-url"><a href="{{ $short_url_href }}" target="_blank" id="copy">{{ $short_url }}</a></span>
        <button class="btn btn-sm btn-outline-success btn-clipboard ml-3" data-clipboard-text="{{ $short_url }}" title="@lang('Copy to clipboard')" data-toggle="tooltip">@lang('Copy')</button>

        <div class="mt-5" id="jssocials"></div>
      </div>
    </div>
  </div>
  </div>
</div>
@endsection

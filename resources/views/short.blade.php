@extends('layouts.home')

@section('css_class', 'view_short')

@section('content')
<div class="container">
  <div class="row justify-content-md-cente mt-5">
    <div class="col">
      @if (session('msgLinkAlreadyExists'))
      <div class="alert alert-success">
        {{ session('msgLinkAlreadyExists') }}
      </div>
      @endif

      <div class="item-detail--created-date"><i class="far fa-clock"></i> {{ $created_at }}</div>
      <div class="item-detail--title">{{ $long_url_title }}</div>

      <div class="row mt-3">
        <div class="col-md-9 content">
          <div class="row">
            <div class="col-md">
              <img src="data:{{$qrCodeData}};base64,{{$qrCodebase64}}" alt="QR Code">
            </div>
            <div class="col-md-9">
              <b>Original URL</b>
              <div class="item-detail--long-url"><a href="{{ $long_url_href }}" target="_blank" title="{{ $long_url_href }}">{{ $long_url }}</a></div>

              <br>
              <b>Short URL</b>
              <div class="item-detail--short-url"><a href="{{ $short_url }}" target="_blank">{{ $short_url }}</a></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

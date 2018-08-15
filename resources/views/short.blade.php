@extends('layouts.home')

@section('css_class', 'view_short')

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
        <i>{{ $views }}</i>
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
        <b>Original URL</b>
        <div class="long-url"><a href="{{ $long_url_href }}" target="_blank" title="{{ $long_url_href }}">{{ $long_url }}</a></div>

        <br>
        <b>Short URL</b> <br>
        <span class="short-url"><a href="{{ $short_url_href }}" target="_blank" id="copy">{{ $short_url }}</a></span>
        <button class="btn btn-outline-success btn-copy" data-clipboard-text="{{ $short_url }}">Copy</button>
      </div>
    </div>
  </div>
  </div>
</div>
@endsection

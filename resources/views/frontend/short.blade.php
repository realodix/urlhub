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
        <div class="long-url"><a href="{{ $long_url }}" target="_blank" title="{{ $long_url }}" data-toggle="tooltip">{{ $long_url_limit }}</a></div>

        <br>

        <b>Short URL</b> <br>
        <span class="short-url"><a href="{{ $long_url }}" target="_blank" id="copy">{{ $short_url }}</a></span>
        <button class="btn btn-sm btn-outline-success btn-clipboard ml-3" data-clipboard-text="{{ $long_url }}" title="Copy to clipboard" data-toggle="tooltip">Copy</button>

        <br><br>

        <b>Share to:</b>
        <div class="socials-share" data-share-url="{{ $long_url }}">
          <button class="btn btn-facebook social-share-network" data-social-network="facebook"><i class="fab fa-facebook-f"></i></button>
          <button class="btn btn-twitter social-share-network" data-social-network="twitter"><i class="fab fa-twitter"></i></button>
          <button class="btn btn-google-plus social-share-network" data-social-network="google"><i class="fab fa-google-plus-g"></i></button>
        </div>
      </div>
    </div>
  </div>
  </div>
</div>
@endsection

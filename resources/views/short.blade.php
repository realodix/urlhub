@extends('layouts.home')

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
        <div class="col-md-8">
          <div class="row">
            <div class="col-md-8">
              <div class="item-detail--long-url">{{ str_limit($long_url, 60, ' (...)') }}</div>
              <div class="item-detail--short-url"><a href="{{ url('/', $short_url) }}" target="_blank">{{ url('/', $short_url) }}</a></div>
            </div>
            <div class="col-md d-flex flex-row-reverse">
              <img src="data:{{$qrCodeData}};base64,{{$qrCodebase64}}" />
            </div>
          </div>
        </div>
        {{-- <div class="col-md">
          <img src="data:{{$qrCodeData}};base64,{{$qrCodebase64}}" />
        </div> --}}
      </div>
    </div>
  </div>
</div>

@endsection

@extends('layouts.home')

@section('content')

<div class="container">
  <div class="row justify-content-md-cente mt-5">
    <div class="col">
      <p>{{ 'CREATED '. $created_at }}</p>

      @if (session('msgLinkAlreadyExists'))
      <div class="alert alert-success">
      {{ session('msgLinkAlreadyExists') }}
      </div>
      @endif

      <p>{{ $long_url }}</p>
      <p><a href="{{ url('/', $short_url) }}" target="_blank">{{ url('/', $short_url) }}</a></p>

      <img src="data:{{$qrCodeData}};base64,{{$qrCodebase64}}" />
    </div>
  </div>
</div>

@endsection

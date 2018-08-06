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
      <p>{{ url('/', $short_url) }}</p>
    </div>
  </div>
</div>

@endsection

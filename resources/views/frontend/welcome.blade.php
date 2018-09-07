@extends('layouts.frontend')

@section('content')
<div class="container home pt-5">
  <div class="row justify-content-md-center">
    <div class="col-lg-8 text-center welcome-msg">We will provide a shortened link for the page you're on.</div>
  </div>

  <div class="row mt-5 justify-content-md-center">
    <div class="col-lg-7">
      {{ html()->form('POST', url('/create'))->class('mt-5')->open() }}
        <div class="input-group input-group-lg">
          <input name="long_url" placeholder="Paste a link to be shortened" class="form-control" id="inputSourceLink" type="text" value="{{ old('long_url') }}">
          <div class="input-group-append">
            <button class="btn btn-primary" type="submit" id="actProcess">Shorten</button>
          </div>
        </div>

        <br>
        <div class="custom-url">
          <div class="title"> Custom URL (optional)</div>
          <div class="site-url">{{$_SERVER['SERVER_NAME']}}/</div>
          <input class="form-control form-control-sm url-field" name="short_url_custom">
        </div>
      {{ html()->form()->close() }}

      @if ($errors->any())
        @foreach ($errors->all() as $error)
        <div class="alert alert-warning mt-3" role="alert">
          {{ $error }}
        </div>
        @endforeach
      @endif

      {{-- @if (session('msgDomainBlocked'))
      <div class="alert alert-warning mt-3" role="alert">
        {{ session('msgDomainBlocked') }}
      </div>
      @endif --}}

    </div>
  </div>
</div>
@endsection

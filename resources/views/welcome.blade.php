@extends('layouts.home')

@section('content')
<div class="container home">
  <div class="row justify-content-md-center mt-5">
    <h1 class="col-lg-8 text-center d-block"><mark>We will provide a shortened link for the page you're on.</mark></h1>
  </div>

  <div class="row mt-5 justify-content-md-center">
    <div class="col-lg-7">
      <form action="{{ url('/create') }}" method="post" class="mt-5">
      @csrf
        <div class="input-group input-group-lg">
          <input name="long_url" placeholder="Paste a link to shorten it" class="form-control" id="inputSourceLink" type="text">
          <div class="input-group-append">
            <button class="btn btn-primary" type="submit" id="actProcess">Shorten</button>
          </div>
        </div>
      </form>

      @if ($errors->has('long_url'))
      <div class="alert alert-warning mt-3" role="alert">
        {{ $errors->first('long_url') }}
      </div>
      @endif

      @if (session('msgDomainBlocked'))
      <div class="alert alert-warning mt-3" role="alert">
        {{ session('msgDomainBlocked') }}
      </div>
      @endif

    </div>
  </div>
</div>
@endsection

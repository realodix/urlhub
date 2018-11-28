@if($errors->any())
  <div class="alert alert-danger" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>

    @foreach($errors->all() as $error)
      {{ $error }}<br/>
    @endforeach
  </div>
@elseif (session('flash_success'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('flash_success') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
@elseif (session('flash_error'))
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('flash_error') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
@elseif (session('msgLinkAlreadyExists'))
  <div class="alert alert-success">
    {{ session('msgLinkAlreadyExists') }}
    @auth<a href="{{route('duplicate', $url->url_key)}}">@lang('Duplicate this')<a>@endauth
  </div>
@endif

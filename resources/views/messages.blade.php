@if($errors->any())
  <div class="block pl-3 pr-4 py-2 mb-4 text-base font-medium text-orange-700 bg-orange-50 border-l-4 border-orange-400" role="alert">
    @foreach($errors->all() as $error)
      {{ $error }}<br/>
    @endforeach
  </div>
@elseif (session('flash_success'))
  <div class="block pl-3 pr-4 py-2 mb-4 text-base font-medium text-emerald-700 bg-emerald-50 border-l-4 border-emerald-400" role="alert">
    {{ session('flash_success') }}
  </div>
@elseif (session('flash_error'))
  <div class="block pl-3 pr-4 py-2 mb-4 text-base font-medium text-red-700 bg-red-50 border-l-4 border-red-400" role="alert">
    {{ session('flash_error') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
@elseif (session('msgLinkAlreadyExists'))
  <div class="block pl-3 pr-4 py-2 mb-4 text-base font-medium text-emerald-700 bg-emerald-50 border-l-4 border-emerald-400">
    {{ session('msgLinkAlreadyExists') }}
    @auth<a href="{{route('duplicate', $url->keyword)}}">@lang('Duplicate this')<a>@endauth
  </div>
@endif

@extends('layouts.frontend')

@section('css_class', 'frontend view_short')

@section('content')
<div class="container mb-5">
  <div class="row header mt-5">
  <div class="col-md-9">

    @include('messages')

    <ul class="list-inline">
      <li class="list-inline-item">
        <i class="far fa-clock"></i>
        <i>{{ $url->created_at->toDayDateTimeString() }}</i>
      </li>
      <li class="list-inline-item">
        <i class="far fa-eye"></i>
        <i><span title="{{number_format($url->clicks)}} clicks" data-toggle="tooltip">{{number_format_short($url->clicks)}}</span></i>
      </li>
    </ul>
    <div class="title">{!! $url->meta_title !!}</div>
  </div>
  </div>

  <div class="row mt-3">
  <div class="col-lg">
    <div class="row body">
      <div class="col-sm-3">
        <img class="qrcode" src="data:{{$qrCode->getContentType()}};base64,{{$qrCode->generate()}}" alt="QR Code">
      </div>
      <div class="col-sm-9">
        <b>@lang('Short URL')</b> <br>
        <span class="short-url"><a href="{{ $url->short_url }}" target="_blank" id="copy">{{ remove_schemes($url->short_url) }}</a></span>
        <button class="btn btn-sm btn-outline-success btn-clipboard ml-3" data-clipboard-text="{{ remove_schemes($url->short_url) }}" title="@lang('Copy to clipboard')" data-toggle="tooltip">@lang('Copy')</button>

        <br> <br>

        <b>@lang('Original URL')</b>
        <div class="long-url"><a href="{{ $url->long_url }}" target="_blank" title="{{ $url->long_url }}" data-toggle="tooltip">{{ url_limit($url->long_url) }}</a></div>

        <div class="mt-5" id="jssocials"></div>

        {!! $embedCode !!}
      </div>
    </div>
  </div>
  </div>

  <div class="row mt-3">
    <div class="col-lg">
      <div class="row body">
        <div class="col">

            <div class="row mb-3">
                <div class="col">
                    <b>@lang('Platforms')</b>
                    <span class="badge badge-primary">@lang('Total:') {{ $url->urlStat->pluck('platform')->unique()->count() }}</span>
                </div>
            </div>
          @foreach($url->urlStat->pluck('platform')->unique() as $platform)
                <div class="card col-lg-3 mb-2 d-inline-block">
                    <div class="card-body">
                        <h5 class="card-title">{{ $platform }}</h5>
                        <h6 class="card-subtitle mb-2 text-muted">
                            {{ $url->urlStat->where('platform', $platform)->count() }}
                        </h6>
                        <p class="card-text"></p>
                    </div>
                </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>

    <div class="row mt-3">
        <div class="col-lg">
            <div class="row body">
                <div class="col">

                    <div class="row mb-3">
                        <div class="col">
                            <b>@lang('Browsers')</b>
                            <span class="badge badge-primary">@lang('Total:') {{ $url->urlStat->pluck('browser')->unique()->count() }}</span>
                        </div>
                    </div>
                    @foreach($url->urlStat->pluck('browser')->unique() as $browser)
                        <div class="card col-lg-3 mb-2 d-inline-block">
                            <div class="card-body">
                                <h5 class="card-title">{{ $browser }}</h5>
                                <h6 class="card-subtitle mb-2 text-muted">
                                    {{ $url->urlStat->where('browser', $browser)->count() }}
                                </h6>
                                <p class="card-text"></p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-lg">
            <div class="row body">
                <div class="col">

                    <div class="row mb-3">
                        <div class="col">
                            <b>@lang('Countries')</b>
                            <span class="badge badge-primary">@lang('Total:') {{ $url->urlStat->pluck('country')->unique()->count() }}</span>
                        </div>
                    </div>

                    @foreach($url->urlStat->pluck('country')->unique() as $country)
                        <div class="card col-lg-3 mb-2 d-inline-block">
                            <div class="card-body">
                                <h5 class="card-title">{{ $country }}</h5>
                                <h6 class="card-subtitle mb-2 text-muted">
                                    {{ $url->urlStat->where('country', $country)->count() }}
                                </h6>
                                <p class="card-text"></p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

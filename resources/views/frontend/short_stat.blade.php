<div class="row mt-3 body">
  <div class="col-lg-12">
    <div class="row ">
      <div class="col">
        <div class="row mb-3">
          <div class="col">
            <b>@lang('Platforms')</b>
            <span class="badge badge-primary">@lang('Total:')
              {{ $url->visit->pluck('platform')->unique()->count() }}</span>
          </div>
        </div>

        @foreach($url->visit->pluck('platform')->unique() as $platform)
        <div class="card col-lg-3 mb-2 d-inline-block">
          <div class="card-body">
            <h5 class="card-title">{{ $platform }}</h5>
            <h6 class="card-subtitle mb-2 text-muted">
              {{ $url->visit->where('platform', $platform)->count() }}
            </h6>
            <p class="card-text"></p>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </div>

  <div class="col-lg-12">
    <div class="row">
      <div class="col">
        <div class="row mb-3">
          <div class="col">
            <b>@lang('Browsers')</b>
            <span class="badge badge-primary">@lang('Total:')
              {{ $url->visit->pluck('browser')->unique()->count() }}</span>
          </div>
        </div>

        @foreach($url->visit->pluck('browser')->unique() as $browser)
        <div class="card col-lg-3 mb-2 d-inline-block">
          <div class="card-body">
            <h5 class="card-title">{{ $browser }}</h5>
            <h6 class="card-subtitle mb-2 text-muted">
              {{ $url->visit->where('browser', $browser)->count() }}
            </h6>
            <p class="card-text"></p>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </div>

  <div class="col-lg-12">
    <div class="row">
      <div class="col">
        <div class="row mb-3">
          <div class="col">
            <b>@lang('Countries')</b>
            <span class="badge badge-primary">@lang('Total:')
              {{ $url->visit->pluck('country')->unique()->count() }}</span>
          </div>
        </div>

        @foreach($url->visit->pluck('country')->unique() as $country)
        <div class="card col-lg-3 mb-2 d-inline-block">
          <div class="card-body">
            <h5 class="card-title">{{ $country }}</h5>
            <h6 class="card-subtitle mb-2 text-muted">
              {{ $url->visit->where('country', $country)->count() }}
            </h6>
            <p class="card-text"></p>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </div>
</div>

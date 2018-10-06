@role('admin')
  <div class="row right_now">
    <div class="col-12 hint">
      <span class="all ml-2"><i class="fas fa-square"></i> @lang('All')</span>
      <span class="me ml-5"><i class="fas fa-square"></i> @lang('Me')</span>
      <span class="guest ml-5"><i class="fas fa-square"></i> @lang('Guest')</span>
    </div>
    <div class="col-md-6 col-xl-5 text-center">
      <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-4">
            <div class="right_now-text--primary">{{$totalShortUrl}}</div>
            <div class="right_now-label">@lang('Urls Shortened')</div>
          </div>
          <div class="col-4">
            <div class="right_now-text--secondary">{{$totalShortUrlById}}</div>
            <div class="right_now-label">@lang('Urls Shortened')</div>
          </div>
          <div class="col-4">
            <div class="right_now-text--tertiary">{{$totalShortUrlByGuest}}</div>
            <div class="right_now-label">@lang('Urls Shortened')</div>
          </div>
        </div>
      </div>
      </div>
    </div>
    <div class="col-md-6 col-xl-5 text-center">
      <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-4">
            <div class="right_now-text--primary">{{$viewCount}}</div>
            <div class="right_now-label">@lang('Clicks & Redirects')</div>
          </div>
          <div class="col-4">
            <div class="right_now-text--secondary">{{$viewCountById}}</div>
            <div class="right_now-label">@lang('Clicks & Redirects')</div>
          </div>
          <div class="col-4">
            <div class="right_now-text--tertiary">{{$viewCountByGuest}}</div>
            <div class="right_now-label">@lang('Clicks & Redirects')</div>
          </div>
        </div>
      </div>
      </div>
    </div>
    <div class="col-md-6 col-xl text-center">
      <div class="card">
      <div class="card-body">
        <div class="right_now-text--primary">{{$userCount}}</div>
        <div class="right_now-label">@lang('Active Users')</div>
      </div>
      </div>
    </div>
  </div>
@else
  <div class="row right_now">
    <div class="col-sm-6 col-lg-3 text-center">
      <div class="card">
      <div class="card-body">
        <div class="right_now-text--primary">{{$totalShortUrlById}}</div>
        <div class="right_now-label">@lang('Urls Shortened')</div>
      </div>
      </div>
    </div>
    <div class="col-sm-6 col-lg-3 text-center">
      <div class="card">
      <div class="card-body">
        <div class="right_now-text--primary">{{$viewCountById}}</div>
        <div class="right_now-label">@lang('Clicks & Redirects')</div>
      </div>
      </div>
    </div>
  </div>
@endrole

@role('admin')
  <div class="row right_now">
    <div class="col-12 hint">
      <span class="all ml-2"><i class="fas fa-square"></i> @lang('All')</span> <span class="me ml-5"><i class="fas fa-square"></i> @lang('Me')</span>
    </div>
    <div class="col-md-6 col-xl-4 text-center">
      <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-6">
            <div class="right_now-text--primary">{{$shortenedUrlCount}}</div>
            <div class="right_now-label">@lang('Urls Shortened')</div>
          </div>
          <div class="col-6">
            <div class="right_now-text--secondary">{{$shortenedUrlCountById}}</div>
            <div class="right_now-label">@lang('Urls Shortened')</div>
          </div>
        </div>
      </div>
      </div>
    </div>
    <div class="col-md-6 col-xl-4 text-center">
      <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-6">
            <div class="right_now-text--primary">{{$viewCount}}</div>
            <div class="right_now-label">@lang('Clicks & Redirects')</div>
          </div>
          <div class="col-6">
            <div class="right_now-text--secondary">{{$viewCountById}}</div>
            <div class="right_now-label">@lang('Clicks & Redirects')</div>
          </div>
        </div>
      </div>
      </div>
    </div>
    <div class="col-md-6 col-xl-2 text-center">
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
        <div class="right_now-text--primary">{{$shortenedUrlCountById}}</div>
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

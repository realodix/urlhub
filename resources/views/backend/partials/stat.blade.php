@role('admin')
  <div class="row right_now">
    <div class="col-12 hint">
      <span class="all ml-2"><i class="fas fa-square"></i> @lang('All')</span>
      <span class="me ml-5"><i class="fas fa-square"></i> @lang('Me')</span>
      <span class="guest ml-5"><i class="fas fa-square"></i> @lang('Guest')</span>
    </div>
    <div class="col-md-6 col-xl-4" style="font-size: 1.25rem; font-weight: 300;">
      <div class="card border-left">
      <div class="card-body">
        <div class="row">
          <div class="col-6 col-sm-5"><b>@lang('Capacity')</b></div>
          <div class="col col-sm"><span title="{{number_format($keyCapacity)}}" data-toggle="tooltip">{{numberToAmountShort($keyCapacity)}}</span></div>
        </div>
        <div class="row">
          <div class="col-6 col-sm-5"><b>@lang('Remaining')</b></div>
          <div class="col col-sm">
            <span title="{{number_format($keyRemaining)}}" data-toggle="tooltip">
              {{numberToAmountShort($keyRemaining)}}
              ({{$remainingPercentage}})
            </span>
          </div>
        </div>
      </div>
      </div>
    </div>
    <div class="col-md-6 col-xl text-center">
      <div class="card border-left">
      <div class="card-body">
        <div class="row">
          <div class="col-4">
            <div class="right_now-text--primary">
              <span title="{{number_format($shortUrlCount)}}" data-toggle="tooltip">{{numberToAmountShort($shortUrlCount)}}</span>
            </div>
            <div class="right_now-label">@lang('Urls Shortened')</div>
          </div>
          <div class="col-4">
            <div class="right_now-text--secondary">
              <span title="{{number_format($shortUrlCountByMe)}}" data-toggle="tooltip">{{numberToAmountShort($shortUrlCountByMe)}}</span>
            </div>
            <div class="right_now-label">@lang('Urls Shortened')</div>
          </div>
          <div class="col-4">
            <div class="right_now-text--tertiary">
              <span title="{{number_format($shortUrlCountByGuest)}}" data-toggle="tooltip">{{numberToAmountShort($shortUrlCountByGuest)}}</span>
            </div>
            <div class="right_now-label">@lang('Urls Shortened')</div>
          </div>
        </div>
      </div>
      </div>
    </div>
  </div>

  <div class="row right_now">
    <div class="col-md-6 col-xl-4 text-center">
      <div class="card border-left">
      <div class="card-body">
        <div class="row">
          <div class="col-6">
            <div class="right_now-text--primary">
              <span title="{{number_format($userCount)}}" data-toggle="tooltip">{{numberToAmountShort($userCount)}}</span>
            </div>
            <div class="right_now-label">@lang('Registered Users')</div>
          </div>
          <div class="col-6">
            <div class="right_now-text--primary">
              <span title="{{number_format($guestCount)}}" data-toggle="tooltip">{{numberToAmountShort($guestCount)}}</span>
            </div>
            <div class="right_now-label">@lang('Guest')</div>
          </div>
        </div>
      </div>
      </div>
    </div>

    <div class="col-md-6 col-xl text-center">
      <div class="card border-left">
      <div class="card-body">
        <div class="row">
          <div class="col-4">
            <div class="right_now-text--primary">
              <span title="{{number_format($clickCount)}}" data-toggle="tooltip">{{numberToAmountShort($clickCount)}}</span>
            </div>
            <div class="right_now-label">@lang('Clicks & Redirects')</div>
          </div>
          <div class="col-4">
            <div class="right_now-text--secondary">
              <span title="{{number_format($clickCountFromMe)}}" data-toggle="tooltip">{{numberToAmountShort($clickCountFromMe)}}</span>
            </div>
            <div class="right_now-label">@lang('Clicks & Redirects')</div>
          </div>
          <div class="col-4">
            <div class="right_now-text--tertiary">
              <span title="{{number_format($clickCountFromGuest)}}" data-toggle="tooltip">{{numberToAmountShort($clickCountFromGuest)}}</span>
            </div>
            <div class="right_now-label">@lang('Clicks & Redirects')</div>
          </div>
        </div>
      </div>
      </div>
    </div>

  </div>
@else
  <div class="row right_now">
    <div class="col-sm-6 col-lg-3 text-center">
      <div class="card border-left">
      <div class="card-body">
        <div class="right_now-text--primary">
          <span title="{{number_format($shortUrlCountByMe)}}" data-toggle="tooltip">{{numberToAmountShort($shortUrlCountByMe)}}</span>
        </div>
        <div class="right_now-label">@lang('Urls Shortened')</div>
      </div>
      </div>
    </div>
    <div class="col-sm-6 col-lg-3 text-center">
      <div class="card border-left">
      <div class="card-body">
        <div class="right_now-text--primary">
          <span title="{{number_format($clickCountFromMe)}}" data-toggle="tooltip">{{numberToAmountShort($clickCountFromMe)}}</span>
        </div>
        <div class="right_now-label">@lang('Clicks & Redirects')</div>
      </div>
      </div>
    </div>
  </div>
@endrole

@extends('layouts.backend')

@section('title', __('Statistics'))

@section('content')
<div class="statistics">
<div class="card">
<div class="card-body">

<div class="alert alert-danger" role="alert">
  Sorry, this page is under development
</div>
  <h3>UrlHub Statistics</h3>
<br>
  <b>Capacity</b>   : <span title="{{number_format($capacity)}}" data-toggle="tooltip">{{numberFormatShort($capacity)}}</span> <br>
  <b>Remaining</b>  : <span title="{{number_format($remaining)}}" data-toggle="tooltip">
                        {{numberFormatShort($remaining)}}
                        @if ($capacity == 0)
                          (0%)
                        @else
                          ({{round(100-((($capacity-$remaining)/$capacity)*100))}}%)
                        @endif
                      </span> <br>

<br>

  <b>Total Short Url</b> <br>
  Value             : <span title="{{number_format($totalShortUrl)}}" data-toggle="tooltip">{{numberFormatShort($totalShortUrl)}}</span> <br>
  Value By Guest    : <span title="{{number_format($totalShortUrlByGuest)}}" data-toggle="tooltip">{{numberFormatShort($totalShortUrlByGuest)}}</span> <br>

<br>

  <b>Total Clicks</b> <br>
  Value             : <span title="{{number_format($totalClicks)}}" data-toggle="tooltip">{{numberFormatShort($totalClicks)}}</span> <br>
  Value By Guest    : <span title="{{number_format($totalClicksByGuest)}}" data-toggle="tooltip">{{numberFormatShort($totalClicksByGuest)}}</span> <br>

<br>

  <b>Total User</b> <br>
  Registered User   : <span title="{{number_format($totalUser)}}" data-toggle="tooltip">{{numberFormatShort($totalUser)}}</span> <br>
  Unregistered User : <span title="{{number_format($totalGuest)}}" data-toggle="tooltip">{{numberFormatShort($totalGuest)}}</span> <br>


</div>
</div>
</div>
@endsection

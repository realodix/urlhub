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
  <b>Capacity</b>   : <span title="{{number_format($keyCapacity)}}" data-toggle="tooltip">{{numberToAmountShort($keyCapacity)}}</span> <br>
  <b>Remaining</b>  : <span title="{{number_format($keyRemaining)}}" data-toggle="tooltip">
                        {{numberToAmountShort($keyRemaining)}}
                        ({{$remainingPercentage}})
                      </span> <br>

<br>

  <b>Total Short Url</b> <br>
  Value             : <span title="{{number_format($shortUrlCount)}}" data-toggle="tooltip">{{numberToAmountShort($shortUrlCount)}}</span> <br>
  Value By Guest    : <span title="{{number_format($shortUrlCountByGuest)}}" data-toggle="tooltip">{{numberToAmountShort($shortUrlCountByGuest)}}</span> <br>

<br>

  <b>Total Clicks</b> <br>
  Value             : <span title="{{number_format($clickCount)}}" data-toggle="tooltip">{{numberToAmountShort($clickCount)}}</span> <br>
  Value By Guest    : <span title="{{number_format($clickCountFromGuest)}}" data-toggle="tooltip">{{numberToAmountShort($clickCountFromGuest)}}</span> <br>

<br>

  <b>Total User</b> <br>
  Registered User   : <span title="{{number_format($userCount)}}" data-toggle="tooltip">{{numberToAmountShort($userCount)}}</span> <br>
  Unregistered User : <span title="{{number_format($guestCount)}}" data-toggle="tooltip">{{numberToAmountShort($guestCount)}}</span> <br>


</div>
</div>
</div>
@endsection

@extends('layouts.backend')

@section('title', __('Statistics'))

@section('content')
<div class="statistics">
<div class="card">
<div class="card-body">

  <h3>UrlHub Statistics</h3>
<br>
  <b>Capacity</b>   : <span title="{{number_format($capacity)}}" data-toggle="tooltip">{{readable_int($capacity)}}</span> <br>
  <b>Remaining</b>  : <span title="{{number_format($remaining)}}" data-toggle="tooltip">
                        {{readable_int($remaining)}}
                        @if ($capacity == 0)
                          (0%)
                        @else
                          ({{round(100-((($capacity-$remaining)/$capacity)*100))}}%)
                        @endif
                      </span> <br>

<br>

  <b>Total Short Url</b> <br>
  Value             : <br>
  Value By Guest    : <br>

<br>

  <b>Total Clicks</b> <br>
  Value             : <br>
  Value By Guest    : <br>

<br>

  <b>Total User</b> <br>
  Registered User   : <br>
  Unregistered User : <br>


</div>
</div>
</div>
@endsection

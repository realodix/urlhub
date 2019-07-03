@extends('layouts.backend')

@section('title', __('Statistics'))

@section('content')
<div class="statistics">
<div class="card">
<div class="card-body">

  <h3>UrlHub Statistics</h3>
<br>
  <b>Capacity</b>             : <br>
  <b>Remaining</b>            : <br>

<br>

  <b>TotalShortUrl</b>        : <br>
  <b>TotalShortUrlByGuest</b> : <br>

<br>

  <b>TotalClicks</b>          : <br>
  <b>TotalClicksByGuest</b>   : <br>

<br>

  <b>TotalUser</b>            : <br>
  <b>TotalGuest</b>           : <br>


</div>
</div>
</div>
@endsection

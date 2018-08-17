@extends('layouts.home')

@section('css_class', 'backend')

@section('content')
<div class="container my-url">

<div class="row mt-5">
<div class="col-md-9 header">
  <div class="title">{{ __('My URLs') }}</div>
</div>
</div>

<div class="row mt-3">
<div class="col-md body">
  <table class="table">
    <thead>
      <tr>
        <th scope="col">Short URL</th>
        <th scope="col">Long URL</th>
        <th scope="col">View</th>
        <th scope="col">Date</th>
      </tr>
    </thead>
    <tbody>
      @forelse ($myurls as $myurl)
      <tr>
        <td><a href="{{url('/'.$myurl->short_url)}}" target="_blank">{{$myurl->short_url}}</a></td>
        <td><a href="{{url('/'.$myurl->long_url)}}" target="_blank">{{$myurl->long_url_mod}}</a></td>
        <td>{{$myurl->views}}</td>
        <td><span title="{{$myurl->created_at}}">{{$myurl->created_at->diffForHumans()}}</span></td>
      </tr>
      @empty
        Data not found
      @endforelse
    </tbody>
  </table>
</div>
</div>
@endsection

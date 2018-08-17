@extends('layouts.home')

@section('css_class', 'backend')

@section('content')
<div class="container all-url">

<div class="row mt-5">
<div class="col-md header">
  <div class="title">{{ __('All URLs') }}</div>
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
      @forelse ($allurls as $allurl)
      <tr>
        <td><a href="{{url('/'.$allurl->short_url)}}" target="_blank">{{$allurl->short_url}}</a></td>
        <td><a href="{{url('/'.$allurl->long_url)}}" target="_blank">{{$allurl->long_url_mod}}</a></td>
        <td>{{$allurl->views}}</td>
        <td><span title="{{$allurl->created_at}}">{{$allurl->created_at->diffForHumans()}}</span></td>
      </tr>
      @empty
        Data not found
      @endforelse
    </tbody>
  </table>
</div>
</div>
@endsection

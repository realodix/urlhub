@extends('layouts.backend')

@section('content')
<div class="my-url">
  <div class="card">
    <div class="card-body">
      <div class="row">
      <div class="col-sm-6">
        <h4 class="card-title mb-3">
          {{ __('My URLs') }}
        </h4>
      </div><!--col-->
      </div><!--row-->

      <table class="table table-responsive-sm table-striped">
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
            <td><a href="{{$myurl->long_url}}" target="_blank">{{$myurl->long_url_mod}}</a></td>
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
</div>
@endsection

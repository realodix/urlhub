@extends('layouts.backend')

@section('content')
<div class="all-url">
  <div class="card">
    <div class="card-header">
      <strong>{{ __('All URLs') }}</strong>
    </div>

    <div class="card-body">
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
</div>
@endsection

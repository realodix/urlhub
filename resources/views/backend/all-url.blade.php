@extends('layouts.backend')

@section('content')
<div class="all-url">
  <div class="card">
    <div class="card-body">
      <div class="row">
      <div class="col-sm-6">
        <h4 class="card-title mb-3">
          {{ __('All URLs') }}
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
            <th scope="col">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($allurls as $allurl)
          <tr>
            <td><a href="{{url('/'.$allurl->short_url)}}" target="_blank">{{$allurl->short_url}}</a></td>
            <td><a href="{{$allurl->long_url}}" target="_blank">{{$allurl->long_url_mod}}</a></td>
            <td>{{$allurl->views}}</td>
            <td><span title="{{$allurl->created_at}}">{{$allurl->created_at->diffForHumans()}}</span></td>
            <td><a role="button" class="btn btn-primary" href="{{ route('short_url.statics', $allurl->short_url) }}" target="_blank"><i class="fa fa-eye"></i> Details</a></td>
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

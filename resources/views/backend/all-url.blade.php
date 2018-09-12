@extends('layouts.backend')

@section('content')
<div class="all-url">
  <div class="card">
    <div class="card-body">
      <div class="row mb-3">
      <div class="col-sm-6">
        <h4 class="card-title mb-0">
          {{ __('All URLs') }}
        </h4>

        <div class="small text-muted">
          We have a total of {{ $total }} URLs.
        </div>
      </div><!--col-->
      </div><!--row-->

      @if (count($allurls) >= 1)
      <table class="table table-responsive-sm table-striped">
        <thead>
          <tr>
            <th scope="col">@lang('Short URL')</th>
            <th scope="col">@lang('Long URL')</th>
            <th scope="col">@lang('View')</th>
            <th scope="col">@lang('Author')</th>
            <th scope="col">@lang('Date')</th>
            <th scope="col">@lang('Actions')</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($allurls as $allurl)
          <tr>
            <td><a href="{{url('/'.$allurl->short_url)}}" target="_blank">{{url('/'.$allurl->short_url)}}</a></td>
            <td><a href="{{$allurl->long_url}}" target="_blank">{{$allurl->long_url_mod}}</a></td>
            <td>{{$allurl->views}}</td>
            <td>
              @if (isset($allurl->user->name))
                {{$allurl->user->name}}
              @else
                Guest
              @endif
            </td>
            <td><span title="{{$allurl->created_at}}">{{$allurl->created_at->diffForHumans()}}</span></td>
            <td>
              <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                <a role="button" class="btn btn-primary" href="{{ route('short_url.statics', $allurl->short_url) }}" target="_blank" title="@lang('Details')"><i class="fa fa-eye"></i></a>
                <a role="button" class="btn btn-danger" href="{{ route('url.delete', $allurl->id) }}" title="@lang('Delete')"><i class="fas fa-trash-alt"></i></a>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
      @else
        No URLs found.
      @endif

      {{ $allurls->links() }}
    </div>
  </div>
</div>
@endsection

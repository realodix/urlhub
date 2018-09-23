@extends('layouts.backend')

@section('title', __('Dashboard'))

@section('content')
<div class="my-url">
  <div class="card">
    <div class="card-body">
      <div class="row mb-3">
      <div class="col-sm-6">
        <h4 class="card-title mb-0">
          {{ __('My URLs') }}
        </h4>
      </div><!--col-->
      <div class="col-sm-6">
        <a class="nav-link float-right" href="{{ url('./') }}" target="_blank" title="@lang('Add URL')"><i class="fas fa-plus"></i></a>
      </div><!--col-->
      </div><!--row-->

      @if (count($myurls) >= 1)
      <table id="dt-myUrls" class="table table-responsive-sm table-striped">
        <thead>
          <tr>
            <th scope="col">@lang('Short URL')</th>
            <th scope="col">@lang('Original URL')</th>
            <th scope="col">@lang('View')</th>
            <th scope="col">@lang('Date')</th>
            <th scope="col">@lang('Actions')</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($myurls as $myurl)
          <tr>
            <td>
              @if ($myurl->short_url_custom == false)
                <a href="{{url('/'.$myurl->short_url)}}" target="_blank">{{urlToDomain(url('/'.$myurl->short_url))}}</a>
              @else
                <a href="{{url('/'.$myurl->short_url_custom)}}" target="_blank">{{urlToDomain(url('/'.$myurl->short_url_custom))}}</a>
              @endif
            </td>
            <td><a href="{{$myurl->long_url}}" target="_blank" title="{{$myurl->long_url}}">{{$myurl->long_url_mod}}</a></td>
            <td>{{$myurl->views}}</td>
            <td><span title="{{$myurl->created_at}}">{{$myurl->created_at->diffForHumans()}}</span></td>
            <td>
              <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                <a role="button" class="btn" href="{{ route('short_url.statics', $myurl->short_url) }}" target="_blank" title="@lang('Details')"><i class="fa fa-eye"></i></a>
                <a role="button" class="btn" href="{{ route('admin.delete', $myurl->id) }}" title="@lang('Delete')"><i class="fas fa-trash-alt"></i></a>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
      @else
        @lang('No data available.')
      @endif
    </div>
  </div>
</div>
@endsection

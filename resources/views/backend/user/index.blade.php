@extends('layouts.backend')

@section('title', __('All Users'))

@section('content')
<div class="all-url">
  <div class="card">
    <div class="card-body">
      <div class="row">
      <div class="col-sm-6">
        <h4 class="card-title mb-3">
          {{ __('All Users') }}
        </h4>
      </div><!--col-->
      </div><!--row-->

      <table id="datatables" class="table table-responsive-sm table-striped">
        <thead>
          <tr>
            <th scope="col">@lang('Username')</th>
            <th scope="col">@lang('E-Mail')</th>
            <th scope="col">@lang('Member Since')</th>
            <th scope="col">@lang('Actions')</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($users as $user)
          <tr>
            <td><a href="{{ route('user.edit', $user->name) }}">{{$user->name}}</a></td>
            <td>{{$user->email}}</td>
            <td><span title="{{$user->created_at}}">{{$user->created_at->diffForHumans()}}</span></td>
            <td>
              <div class="btn-group" role="group" aria-label="Basic example">
                <div class="btn-group" role="group" aria-label="Basic example">
                  <a role="button" class="btn btn-primary" href="{{ route('user.edit', $user->name) }}" title="@lang('Details')"><i class="fa fa-eye"></i></a>
                  <a role="button" class="btn btn-danger" href="{{ route('user.change-password', $user->name) }}" title="@lang('Change Password')"><i class="fas fa-key"></i></a>
                </div>
              </div>
            </td>
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

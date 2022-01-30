@extends('layouts.backend')

@section('title', Str::title(Auth::user()->name) .' ‹ '. __('Edit Profile'))

@section('content')

@include('messages')

<div class="md:grid md:grid-cols-3 md:gap-6">
  <div class="md:col-span-1 flex justify-between">
    <div class="px-4 sm:px-0">
      <h3 class="text-lg font-medium text-gray-900">@lang('Profile Information')</h3>

      <p class="mt-1 text-sm text-gray-600">
        @lang("Update your account's profile information and email address.")
      </p>
    </div>
  </div>
  <div class="mt-5 md:mt-0 md:col-span-2">
    <form method="post" action="{{route('user.update', $user->getRouteKey())}}">
    @csrf
      <div class="px-4 py-5 bg-white sm:p-6 shadow sm:rounded-tl-md sm:rounded-tr-md">
        <div class="grid grid-cols-6 gap-6">
          <div class="col-span-6 sm:col-span-4">
            <label for="name" class="block font-medium text-sm text-gray-700">@lang('Username')</label>
            <input value="{{$user->name}}" id="name" type="text" name="name" class="form-input bg-nord6 text-nord2 mt-1" disabled>
            <small class="block text-red-400"><i>@lang('Usernames cannot be changed.')</i></small>
          </div>
          <div class="col-span-6 sm:col-span-4">
            <label for="email" class="block font-medium text-sm text-gray-700">@lang('E-mail Address')</label>
            <input value="{{$user->email}}" id="email" type="email" name="email" class="form-input mt-1">
          </div>
        </div>
      </div>
      <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md">
        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
          @lang('Save')
        </button>
      </div>
    </form>
  </div>
</div>
@endsection

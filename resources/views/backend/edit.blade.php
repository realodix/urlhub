@extends('layouts.backend')

@section('title', Str::title(Auth::user()->name) .' ‹ '. __('Change Password'))

@section('content')

@include('partials/messages')

<main class="flex flex-wrap">
  <div class="md:w-3/12 flex justify-between">
    <div class="px-4 sm:px-0">
      <h3 class="text-lg font-medium text-slate-900">@lang('My URLs')</h3>

      <p class="mt-1 text-sm text-slate-600">
        @lang('Edit URL')
      </p>
    </div>
  </div>
  <div class="w-full md:w-6/12 mt-5 md:mt-0 md:ml-4 bg-white">
    <form method="post" action="{{route('short_url.edit.post', $url->getRouteKey())}}">
    @csrf
      <div class="bg-white px-4 py-5 sm:p-6 shadow sm:rounded-tl-md sm:rounded-tr-md">
        <div class="grid grid-cols-6 gap-6">
          <div class="col-span-6 sm:col-span-4">
            <label for="short-url" class="block font-medium text-sm text-slate-700">@lang('Short URL')</label>
            <span class="short-url">{{urlDisplay($url->short_url, false)}}</span>
          </div>

          <div class="col-span-6 sm:col-span-4">
            <label for="meta-title" class="block font-medium text-sm text-slate-700">@lang('Title')</label>
            <input id="meta-title" type="text" name="meta_title" placeholder="@lang('Title')" required value="{{$url->meta_title}}" class="form-input">
          </div>

          <div class="col-span-6 sm:col-span-4">
            <label for="long-url" class="block font-medium text-sm text-slate-700">@lang('Confirmation')</label>
            <input id="long-url" type="text" name="long_url" placeholder="@lang('Enter your long url')" required value="{{$url->long_url}}" class="form-input">
          </div>
        </div>
      </div>
      <div class="flex items-center justify-end px-4 py-3 bg-slate-50 text-right sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md">
        <button type="submit" class="button">
          @lang('Save Changes')
        </button>
      </div>
    </form>
  </div>
</main>
@endsection

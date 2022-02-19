@extends('layouts.frontend')

@section('css_class', 'frontend home')

@section('content')
<div class="pt-16 sm:pt-28">
  @if (! Auth::check() and ! Config::get('urlhub.public_site'))
   <div class="flex flex-wrap md:justify-center">
    <div class="lg:w-8/12 text-5xl font-thin text-gray-600 text-center welcome-msg">@lang('Please login to shorten URLs')</div>
  </div>
  <div class="flex flex-wrap md:justify-center mt-12">
    <div class="lg:w-7/12">
      @include('partials/messages')
    </div>
  </div>
  @else
  <div class="flex flex-wrap md:justify-center">
    <div class="lg:w-8/12 text-3xl sm:text-5xl font-thin text-gray-600 text-center welcome-msg">Shorten links to better spread your story on social media</div>
  </div>

  <div class="flex flex-wrap md:justify-center px-4 sm:px-0 mt-12 ">
    <div class="lg:w-7/12">
      <form method="post" action="{{route('createshortlink')}}" class="mt-12 mb-4" id="formUrl">
      @csrf
        <div class="mt-1 relative shadow-md">
          <input type="text" name="long_url" id="inputSourceLink" value="{{ old('long_url') }}" placeholder="@lang('Paste a link to be shortened')" class="text-xl block w-full py-1.5 pl-7 pr-12 rounded-md">
          <div class="absolute inset-y-0 right-0 flex items-center">
            <button type="submit" id="actProcess" class="text-xl bg-teal-700 hover:bg-teal-600 focus:bg-teal-900 text-white rounded-r-md rounded-l-none">@lang('Shorten')</button>
          </div>
        </div>

        <br>

        <div class="custom-url sm:mt-8">
          <b>@lang('Custom URL (optional)')</b>
          <span class="block font-light mb-4">@lang('Replace clunky URLs with meaningful short links that get more clicks.')</span>
          <div class="inline text-2xl">{{$_SERVER['SERVER_NAME']}}/</div>
          <input id="custom_key" name="custom_key"
            class="text-2xl text-orange-400 bg-transparent border-b-4 border-orange-500 focus:outline-none px-2" >
          <small id="link-availability-status"
            class="block ml-4"></small>
        </div>
      </form>

      @include('partials/messages')

    </div>
  </div>
  @endif
</div>
@endsection

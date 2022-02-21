@extends('layouts.frontend')

@section('css_class', 'frontend home')

@section('content')
<div class="pt-16 sm:pt-28">
  @if (! Auth::check() and ! Config::get('urlhub.public_site'))
   <div class="flex flex-wrap md:justify-center">
    <div class="w-full md:w-8/12 text-5xl font-thin text-gray-600 text-center welcome-msg">@lang('Please login to shorten URLs')</div>
  </div>
  <div class="flex flex-wrap md:justify-center mt-12">
    <div class="w-full md:w-7/12">
      @include('partials/messages')
    </div>
  </div>
  @else
  <div class="flex flex-wrap md:justify-center">
    <div class="w-full md:w-8/12 text-3xl sm:text-5xl font-thin text-gray-600 text-center welcome-msg">Shorten links to better spread your story on social media</div>
  </div>

  <div class="flex flex-wrap justify-center px-4 lg:px-0 mt-12 ">
    <div class="w-full max-w-4xl">
      <form method="post" action="{{route('createshortlink')}}" class="mt-12 mb-4" id="formUrl">
      @csrf
        <div class="mt-1 text-center">
          <input type="text" name="long_url" id="inputSourceLink" value="{{ old('long_url') }}" placeholder="@lang('Shorten your link')"
            class="text-xl h-16 px-2 md:px-6
              w-full md:w-4/6 rounded-t-md md:rounded-l-md md:rounded-r-none outline-none focus:outline-1 focus:outline-[#9b97e8]">

          <button type="submit" id="actProcess"
            class="text-lg bg-uh-indigo-600 hover:bg-uh-indigo-700 focus:bg-uh-indigo-600 text-white h-16 align-top
              w-full md:w-1/6 rounded-t-none md:rounded-l-none md:rounded-r-md">@lang('Shorten')</button>
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

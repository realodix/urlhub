@extends('layouts.frontend')

@section('css_class', 'frontend home')

@section('content')
<div class="pt-16 sm:pt-28">
  @if (! Auth::check() and ! Config::get('urlhub.public_site'))
  <div class="flex flex-wrap md:justify-center">
    <div class="w-full md:w-8/12 font-thin text-5xl text-slate-600 text-center welcome-msg">
      @lang('Please login to shorten URLs')</div>
  </div>
  <div class="flex flex-wrap md:justify-center mt-12">
    <div class="w-full md:w-7/12">
      @include('partials/messages')</div>
  </div>
  @else
  <div class="flex flex-wrap md:justify-center">
    <h1 class="mx-auto max-w-md md:max-w-3xl relative z-10
      font-bold text-uh-indigo-600 text-center md:text-4xl xl:text-5xl text-3xl !leading-tight"
    >
      Simple URL shortener <br>
      <span class="font-thin">for individuals &amp; businesses.</span>
    </h1>
  </div>

  <div class="flex flex-wrap justify-center mt-12 px-4 lg:px-0">
    <div class="w-full max-w-4xl">
      <form method="post" action="{{route('createshortlink')}}" class="mb-4 mt-12" id="formUrl">
      @csrf
        <div class="mt-1 text-center">
          <input type="text" name="long_url" id="inputSourceLink" value="{{ old('long_url') }}" placeholder="@lang('Shorten your link')"
            class="w-full md:w-4/6 px-2 md:px-4 h-12 sm:h-14
              rounded-t-md md:rounded-l-md md:rounded-r-none outline-none focus:outline-1 focus:outline-uh-indigo-300
              text-xl">
          <button type="submit" id="actProcess"
            class="w-full md:w-1/6 h-12 sm:h-14 align-top rounded-t-none md:rounded-l-none md:rounded-r-md
              text-lg text-white bg-uh-indigo-600 hover:bg-uh-indigo-700 focus:bg-uh-indigo-600"
          >
            @lang('Shorten')
          </button>
        </div>

        <br>

        <div class="custom-url sm:mt-8">
          <b>@lang('Custom URL (optional)')</b>
          <span class="block mb-4 font-light">
            @lang('Replace clunky URLs with meaningful short links that get more clicks.')</span>
          <div class="inline text-2xl">
            {{$_SERVER['SERVER_NAME']}}/</div>
          <input id="custom_key" name="custom_key"
            class="px-2 text-2xl text-orange-400 bg-transparent border-b-4 border-orange-500 focus:outline-none">
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

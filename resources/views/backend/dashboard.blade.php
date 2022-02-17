@extends('layouts.backend')

@section('title', __('Dashboard'))

@section('content')
<main>
  <div class="bg-white p-4 shadow sm:rounded-md mb-4">
  @role('admin')
    <div class="flex flex-wrap">
      <div class="w-full sm:w-1/4">
        <span class="text-cyan-600"><i class="fas fa-square mr-2"></i>@lang('All')</span>
        <span class="text-teal-600 ml-5"><i class="fas fa-square mr-2"></i>@lang('Me')</span>
        <span class="text-orange-600 ml-5"><i class="fas fa-square mr-2"></i>@lang('Guest')</span>
      </div>
      <div class="text-uh-1 mt-8 sm:mt-0">
        <b>@lang('Free Space'):</b> <span class="font-light">{{numberToAmountShort($keyRemaining)}} of {{numberToAmountShort($keyCapacity)}} ({{$keyRemaining_Percent}})</span>
      </div>
    </div>

    <div class="flex flex-wrap sm:mt-8">
      <div class="w-full sm:w-1/4">
        <div class="block">
          <b class="text-uh-1">@lang('Urls Shortened'):</b>
          <span class="text-cyan-600">{{numberToAmountShort($totalUrl)}}</span> -
          <span class="text-teal-600">{{numberToAmountShort($urlCount_Me)}}</span> -
          <span class="text-orange-600">{{numberToAmountShort($urlCount_Guest)}}</span>
        </div>
        <div class="block">
          <b class="text-uh-1">@lang('Clicks'):</b>
          <span class="text-cyan-600">{{numberToAmountShort($totalClick)}}</span> -
          <span class="text-teal-600">{{numberToAmountShort($clickCount_Me)}}</span> -
          <span class="text-orange-600">{{numberToAmountShort($clickCount_Guest)}}</span>
        </div>
      </div>
      <div class="text-uh-1 w-full sm:w-1/4 mt-4 sm:mt-0">
        <div class="block">
          <b>@lang('Registered Users'):</b> <span class="font-light">{{numberToAmountShort($userCount)}}</span>
        </div>
        <div class="block">
          <b cl>@lang('Guest'):</b> <span class="font-light">{{numberToAmountShort($guestCount)}}</span>
        </div>
      </div>
    </div>
  @else
    <div class="flex flex-wrap">
      <div class="w-full sm:w-1/4">
        <span class="text-lg sm:text-2xl font-light">@lang('Urls Shortened'):</span> <span class="text-lg sm:text-2xl font-light">{{numberToAmountShort($urlCount_Me)}}</span>
      </div>
      <div class="w-full sm:w-1/4">
        <span class="text-lg sm:text-2xl font-light">@lang('Clicks & Redirects'):</span> <span class="text-lg sm:text-2xl font-light">{{numberToAmountShort($clickCount_Me)}}</span>
      </div>
    </div>
  @endrole
  </div>

  <div class="bg-white p-4 shadow sm:rounded-md">
    <div class="flex mb-8">
      <div class="w-1/2">
        <span class="text-2xl text-uh-1">
          @lang('My URLs')
        </span>
      </div>
      <div class="w-1/2 text-right">
        <a href="{{ url('./') }}" target="_blank" title="@lang('Add URL')" class="text-sm text-white bg-uh-2b hover:bg-uh-2c active:bg-uh-2b p-2 rounded-md">
          @lang('Add URL')
        </a>
      </div>
    </div>

    @include('partials/messages')

    @livewire('my-url-table')
  </div>
</main>
@endsection

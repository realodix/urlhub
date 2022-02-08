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
      <div class=" mt-8 sm:mt-0">
        <b>@lang('Free Space'):</b> <span class="font-light">{{numberToAmountShort($keyRemaining)}} of {{numberToAmountShort($keyCapacity)}} ({{$remainingPercentage}})</span>
      </div>
    </div>
totalShortLink
    <div class="flex flex-wrap sm:mt-8">
      <div class="w-full sm:w-1/4">
        <div class="block">
          <b>@lang('Urls Shortened'):</b>
          <span class="text-cyan-600">{{numberToAmountShort($tShortLink)}}</span> -
          <span class="text-teal-600">{{numberToAmountShort($tShortLinkByMe)}}</span> -
          <span class="text-orange-600">{{numberToAmountShort($tShortLinkByGuest)}}</span>
        </div>
        <div class="block">
          <b>@lang('Clicks & Redirects'):</b>
          <span class="text-cyan-600">{{numberToAmountShort($tClick)}}</span> -
          <span class="text-teal-600">{{numberToAmountShort($tClickFromMe)}}</span> -
          <span class="text-orange-600">{{numberToAmountShort($tClickFromGuest)}}</span>
        </div>
      </div>
      <div class="w-full sm:w-1/4 mt-4 sm:mt-0">
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
        <span class="text-lg sm:text-2xl font-light">@lang('Urls Shortened'):</span> <span class="text-lg sm:text-2xl font-light">{{numberToAmountShort($tShortLinkByMe)}}</span>
      </div>
      <div class="w-full sm:w-1/4">
        <span class="text-lg sm:text-2xl font-light">@lang('Clicks & Redirects'):</span> <span class="text-lg sm:text-2xl font-light">{{numberToAmountShort($tClickFromMe)}}</span>
      </div>
    </div>
  @endrole
  </div>

  <div class="bg-white p-4 shadow sm:rounded-md">
    <div class="flex mb-8">
      <div class="w-1/2">
        <span class="font-bold text-2xl">
          @lang('My URLs')
        </span>
      </div>
      <div class="w-1/2 text-right">
        <a href="{{ url('./') }}" target="_blank" title="@lang('Add URL')" class="font-bold text-2xl text-violet-800">
          <i class="fas fa-plus"></i>
        </a>
      </div>
    </div>

    @include('partials/messages')

    @livewire('my-url-table')
  </div>
</main>
@endsection

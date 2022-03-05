@extends('layouts.backend')

@section('title', __('Dashboard'))

@section('content')
<main>
  <div class="mb-4 bg-white p-4 shadow sm:rounded-md">
  @role('admin')
    <div class="flex flex-wrap">
      <div class="w-full sm:w-1/4">
        <span class="text-cyan-600"><i class="fas fa-square mr-2"></i>{{__('All')}}</span>
        <span class="text-teal-600 ml-5"><i class="fas fa-square mr-2"></i>{{__('Me')}}</span>
        <span class="text-orange-600 ml-5"><i class="fas fa-square mr-2"></i>{{__('Guest')}}</span>
      </div>
      <div class="mt-8 sm:mt-0 text-uh-1 ">
        <b>{{__('Free Space')}}:</b>
        <span class="font-light">{{numberToAmountShort($keyRemaining)}} {{__('of')}} {{numberToAmountShort($keyCapacity)}} ({{$keyRemaining_Percent}})</span>
      </div>
    </div>

    <div class="flex flex-wrap sm:mt-8">
      <div class="w-full sm:w-1/4">
        <div class="block">
          <b class="text-uh-1">{{__('URLs Shortened')}}:</b>
          <span class="text-cyan-600">{{numberToAmountShort($totalUrl)}}</span> -
          <span class="text-teal-600">{{numberToAmountShort($urlCount_Me)}}</span> -
          <span class="text-orange-600">{{numberToAmountShort($urlCount_Guest)}}</span>
        </div>
        <div class="block">
          <b class="text-uh-1">{{__('Clicks')}}:</b>
          <span class="text-cyan-600">{{numberToAmountShort($totalClick)}}</span> -
          <span class="text-teal-600">{{numberToAmountShort($clickCount_Me)}}</span> -
          <span class="text-orange-600">{{numberToAmountShort($clickCount_Guest)}}</span>
        </div>
      </div>
      <div class="text-uh-1 w-full sm:w-1/4 mt-4 sm:mt-0">
        <div class="block">
          <b>{{__('Registered Users')}}:</b>
          <span class="font-light">{{numberToAmountShort($userCount)}}</span>
        </div>
        <div class="block">
          <b cl>{{__('Guest')}}:</b>
          <span class="font-light">{{numberToAmountShort($guestCount)}}</span>
        </div>
      </div>
    </div>
  @else
    <div class="flex flex-wrap">
      <div class="w-full sm:w-1/4">
        <span class="font-semibold text-md sm:text-2xl">{{__('URLs')}}:</span>
        <span class="font-light text-lg sm:text-2xl">{{numberToAmountShort($urlCount_Me)}}</span>
      </div>
      <div class="w-full sm:w-1/4">
        <span class="font-semibold text-lg sm:text-2xl">{{__('Clicks')}}:</span>
        <span class="font-light text-lg sm:text-2xl">{{numberToAmountShort($clickCount_Me)}}</span>
      </div>
    </div>
  @endrole
  </div>

  <div class="bg-white p-4 shadow sm:rounded-md">
    <div class="flex mb-8">
      <div class="w-1/2">
        <span class="text-2xl text-uh-1">{{__('My URLs')}}</span>
      </div>
      <div class="w-1/2 text-right">
        <a href="{{ url('./') }}" target="_blank" title="{{__('Add URL')}}"
          class="inline-flex ml-4 px-4 py-2 items-center whitespace-nowrap
            focus:outline-none focus:ring-2 focus:ring-offset-2 leading-5 border border-transparent rounded-md shadow-sm
            text-sm font-medium text-white bg-uh-indigo-600 hover:bg-uh-indigo-700 focus:ring-uh-indigo-500"
        >
          <i class="fa-solid fa-plus mr-2"></i>
          {{__('Add URL')}}
        </a>
      </div>
    </div>

    @include('partials/messages')

    @livewire('my-url-table')
  </div>
</main>
@endsection

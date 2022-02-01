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

  <div class="flex flex-wrap sm:mt-8">
    <div class="w-full sm:w-1/4">
      <div class="block">
        <b>@lang('Urls Shortened'):</b>
        <span class="text-cyan-600">{{numberToAmountShort($shortUrlCount)}}</span> -
        <span class="text-teal-600">{{numberToAmountShort($shortUrlCountByMe)}}</span> -
        <span class="text-orange-600">{{numberToAmountShort($shortUrlCountByGuest)}}</span>
      </div>
      <div class="block">
        <b>@lang('Clicks & Redirects'):</b>
        <span class="text-cyan-600">{{numberToAmountShort($clickCount)}}</span> -
        <span class="text-teal-600">{{numberToAmountShort($clickCountFromMe)}}</span> -
        <span class="text-orange-600">{{numberToAmountShort($clickCountFromGuest)}}</span>
      </div>
    </div>
    <div class="w-full sm:w-1/4 mt-4 sm:mt-0">
      <div class="block">
        <b>@lang('Registered Users'):</b> <span class="font-light">{{numberToAmountShort($guestCount)}}</span>
      </div>
      <div class="block">
        <b cl>@lang('Guest'):</b> <span class="font-light">{{numberToAmountShort($shortUrlCountByGuest)}}</span>
      </div>
    </div>
  </div>
@else
  <div class="flex flex-wrap">
    <div class="w-full sm:w-1/4">
      <span class="text-lg sm:text-2xl font-light">@lang('Urls Shortened'):</span> <span class="text-lg sm:text-2xl font-light">{{numberToAmountShort($shortUrlCountByMe)}}</span>
    </div>
    <div class="w-full sm:w-1/4">
      <span class="text-lg sm:text-2xl font-light">@lang('Clicks & Redirects'):</span> <span class="text-lg sm:text-2xl font-light">{{numberToAmountShort($clickCountFromMe)}}</span>
    </div>
  </div>
@endrole
</div>

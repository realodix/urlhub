<div class="bg-white px-4 py-5 sm:p-6 shadow sm:rounded-md mt-6">
  <div>
    <div class="mb-4">
      <b>@lang('Platforms')</b>
      <span class="badge-rounded bg-indigo-400/10 text-indigo-600">
        {{$url->visit->pluck('platform')->unique()->count()}}</span>
    </div>

    <div class="flex flex-wrap gap-2">
    @foreach($url->visit->pluck('platform')->unique() as $platform)
      <div class="basis-auto sm:basis-1/6 border border-gray-300 rounded-md p-2 sm:p-2">
        <h5 class="card-title">{{ $platform }}</h5>
        <h6 class="">
          {{ $url->visit->where('platform', $platform)->count() }}
        </h6>
      </div>
    @endforeach
    </div>
  </div>

  <div class="mt-8">
    <div class="mb-4">
      <b>@lang('Browsers')</b>
      <span class="badge-rounded bg-indigo-400/10 text-indigo-600">
        {{$url->visit->pluck('browser')->unique()->count()}}</span>
    </div>

    <div class="flex flex-wrap gap-2">
    @foreach($url->visit->pluck('browser')->unique() as $browser)
      <div class="basis-auto sm:basis-1/6 border border-gray-300 rounded-md p-2 sm:p-2">
        <h5 class="card-title">{{$browser}}</h5>
        <h6 class="">
          {{$url->visit->where('browser', $browser)->count()}}
        </h6>
      </div>
    @endforeach
    </div>
  </div>

  <div class="mt-8">
    <div class="mb-4">
      <b>@lang('Countries')</b>
      <span class="badge-rounded bg-indigo-400/10 text-indigo-600">
        {{$url->visit->pluck('country')->unique()->count()}}</span>
    </div>

    <div class="flex flex-wrap gap-2">
    @foreach($url->visit->pluck('country')->unique() as $country)
      <div class="basis-auto sm:basis-1/6 border border-gray-300 rounded-md p-2 sm:p-2">
        <h5 class="card-title">{{$country}}</h5>
        <h6 class="">
          {{$url->visit->where('country', $country)->count()}}
        </h6>
      </div>
    @endforeach
    </div>
  </div>
</div>

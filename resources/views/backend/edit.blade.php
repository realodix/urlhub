@extends('layouts.backend')

@section('title', Str::title(Auth::user()->name) .' ‹ '. __('Edit URL Details'))

@section('content')
    @include('partials/messages')

    <main class="flex flex-wrap">
        <div class="md:w-3/12 flex justify-between">
            <div class="px-4 sm:px-0">
                <h3 class="text-lg font-medium text-slate-900">{{__('Edit URL Details')}}</h3>
                <br>
                <div class="inline sm:block mr-2 text-sm text-slate-600">
                    @svg('icon-user', 'mr-1')
                    {{$url->user->name}}
                </div>
                <div class="inline sm:block text-sm text-slate-600">
                    @svg('icon-calendar', 'mr-1')
                    <span title="{{$url->created_at->toDayDateTimeString()}}">{{$url->created_at->diffForHumans()}}</span>
                </div>
            </div>
        </div>
        <div class="w-full md:w-8/12 lg:w-6/12 mt-5 md:mt-0 md:ml-4">
            <form method="post" action="{{route('short_url.edit.post', $url->getRouteKey())}}">
            @csrf
                <div class="common-card-style sm:rounded-b-none px-4 py-5 sm:p-6">
                    <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6 lg:col-span-4">
                            <label for="short-url" class="block font-medium text-sm text-slate-700">{{__('Short URL')}}</label>
                            <span class="short-url">{{urlDisplay($url->short_url, false)}}</span>
                        </div>

                        <div class="col-span-6">
                            <label for="meta-title" class="block font-medium text-sm text-slate-700">{{__('Title')}}</label>
                            <input id="meta-title" type="text" name="meta_title" placeholder="{{__('Title')}}" required
                                value="{{$url->meta_title}}" class="form-input">
                        </div>

                        <div class="col-span-6">
                            <label for="long-url" class="block font-medium text-sm text-slate-700">{{__('DestinationURL')}}</label>
                            <input id="long-url" type="text" name="long_url" placeholder="{{__('Enter your long url')}}"
                                required value="{{$url->long_url}}" class="form-input">
                        </div>
                    </div>
                </div>
                <div class="common-card-style bg-bg-primary sm:bg-slate-50 sm:rounded-t-none
                    flex items-center justify-end px-4 py-3 sm:px-6
                    text-right border-t"
                >
                    <button type="submit" class="btn btn-primary btn-sm">
                        {{__('Save Changes')}}
                    </button>
                </div>
            </form>
        </div>
    </main>
@endsection

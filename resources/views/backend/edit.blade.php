@extends('layouts.backend')

@section('title', Str::title(auth()->user()->name) .' ‹ '. __('Edit URL Details'))

@section('content')
    @include('partials/messages')

    <main class="flex flex-wrap">
        <div class="md:w-3/12 flex justify-between">
            <div class="px-4 sm:px-0">
                <h3 class="text-lg font-medium text-slate-900">{{__('Edit URL Details')}}</h3>
                <br>
                <div class="inline sm:block mr-2 text-sm text-slate-600">
                    @svg('icon-person', 'mr-1')
                    {{$url->author->name}}
                </div>
                <div class="inline sm:block text-sm text-slate-600">
                    @svg('icon-calendar', 'mr-1')
                    <span title="{{$url->created_at->toDayDateTimeString()}}">{{$url->created_at->diffForHumans()}}</span>
                </div>

                @if ($url->created_at != $url->updated_at)
                <div class="inline sm:block text-sm text-slate-600">
                    @svg('icon-updated', 'mr-1 font-bold')
                    <span title="{{$url->updated_at->toDayDateTimeString()}}">{{$url->updated_at->diffForHumans()}}</span>
                </div>
                @endif
            </div>
        </div>
        <div class="w-full md:w-8/12 lg:w-6/12 mt-5 md:mt-0 md:ml-4">
            <form method="post" action="{{route('dboard.url.edit.store', $url)}}">
            @csrf
                <div class="common-card-style">
                    <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6 lg:col-span-4">
                            <label for="short-url" class="form-label">{{__('Short URL')}}</label>
                            <span class="short-url text-uh-blue">{{urlDisplay($url->short_url, scheme: false)}}</span>
                        </div>

                        <div class="col-span-6">
                            <label class="form-label">{{__('Title')}}</label>
                            <input name="title" placeholder="{{__('Title')}}" required
                                value="{{$url->title}}" class="form-input">
                        </div>

                        <div class="col-span-6">
                            <label for="long-url" class="form-label">{{__('Destination URL')}}</label>
                            <input id="long-url" name="long_url" placeholder="http://www.my_long_url.com"
                                required value="{{$url->destination}}" class="form-input">
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-4 text-right">
                        <button type="submit" class="btn btn-primary btn-sm">
                            {{__('Save Changes')}}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </main>
@endsection

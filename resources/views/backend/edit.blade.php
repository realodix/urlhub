@extends('layouts.backend')

@section('title', __('Edit Link').' "'.$url->keyword.'"'.' ‹ '.str()->title(auth()->user()->name))

@section('content')
<div class="container-alt max-w-340 flex flex-wrap">
    <div class="md:w-3/12 flex justify-between">
        <div class="px-4 sm:px-0">
            <h3>{{ __('Edit URL Details') }}</h3>
            <br>
            <div class="inline sm:block mr-2 text-sm text-slate-600 dark:text-dark-400">
                @svg('icon-person', 'mr-1')
                {{ $url->author->name }}
            </div>
            <div class="inline sm:block text-sm text-slate-600 dark:text-dark-400">
                @svg('icon-calendar', 'mr-1')
                <span title="{{ $createdAt->toDayDateTimeString() }} ({{ $createdAt->getOffsetString() }})">{{ $createdAt->diffForHumans() }}</span>
            </div>

            @if ($createdAt != $updatedAt)
            <div class="inline sm:block text-sm text-slate-600 dark:text-dark-400">
                @svg('icon-updated', 'mr-1 font-bold')
                <span title="{{ $updatedAt->toDayDateTimeString() }} ({{ $updatedAt->getOffsetString() }})">{{ $updatedAt->diffForHumans() }}</span>
            </div>
            @endif
        </div>
    </div>
    <div class="w-full md:w-8/12 lg:w-6/12 mt-5 md:mt-0 md:ml-4">
        @include('partials/messages')

        <form method="post" action="{{ route('link.update', $url) }}">
        @csrf
            <div class="content-container card card-fluid">
                <div class="grid grid-cols-6 gap-6">
                    <div class="col-span-6 lg:col-span-4">
                        <label class="form-label">{{ __('Short URL') }}</label>
                        <span class="text-primary-700 dark:text-emerald-500">{{ urlFormat($url->short_url, scheme: false) }}</span>
                    </div>

                    <div class="col-span-6">
                        <label class="form-label">{{ __('Title') }}</label>
                        <input name="title" required placeholder="{{ __('Title') }}" value="{{ $url->title }}" class="form-input">
                    </div>

                    <div class="col-span-6">
                        <label class="form-label">{{ __('Destination URL') }}</label>
                        <input name="long_url" required placeholder="http://www.my_long_url.com" value="{{ $url->destination }}" class="form-input">
                    </div>

                    @if (settings()->forward_query && $url->author->forward_query)
                        <div class="col-span-6">
                            <label class="form-label">Parameter Passing</label>
                            <p class="font-light text-sm dark:text-dark-400">Forward query parameters from your short link to the destination URL. For example, <code class="text-slate-600">https://short.link/abc?utm_medium=social</code> will redirect to <code class="text-slate-600">https://example.com?utm_medium=social</code>.</p>
                            <label class="switch float-right mt-6">
                                <input type="checkbox" name="forward_query" value="1" {{ $url->forward_query ? 'checked' : '' }}>
                                <span class="slider"></span>
                            </label>
                        </div>
                    @else
                        <input type="hidden" name="forward_query" value="{{ $url->forward_query ? true : false }}">
                    @endif
                </div>

                <div class="flex items-center justify-end mt-8 text-right">
                    <button type="submit" class="btn btn-primary btn-sm">
                        {{ __('Save Changes') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

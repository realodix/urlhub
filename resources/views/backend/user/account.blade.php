@extends('layouts.backend')

@section('title', __('Edit Account').' "'.$user->name.'"'.' ‹ '.str()->title(auth()->user()->name))

@section('content')

<div class="container-alt max-w-340">
    <div class="flex flex-wrap">
        <div class="md:w-3/12 flex justify-between">
            <div class="px-4 sm:px-0">
                <h3>{{ __('Account Information') }}</h3>

                <p class="mt-1 text-sm text-slate-600 dark:text-dark-400">
                    {{ __("Update your account information.") }}
                </p>
            </div>
        </div>
        <div class="w-full md:w-8/12 lg:w-6/12 mt-5 md:mt-0 md:ml-4">
            @include('partials/messages')

            <form method="post" action="{{ route('user.update', $user) }}">
            @csrf
                <div class="content-container card card-fluid">
                    <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6 lg:col-span-4">
                            <label class="form-label">{{ __('Username') }}</label>
                            <input name="name" value="{{ $user->name }}" class="form-input mt-1" disabled>
                            <small class="block text-red-600 dark:text-red-500"><i>{{ __('Usernames cannot be changed.') }}</i></small>
                        </div>
                        <div class="col-span-6 lg:col-span-4">
                            <label class="form-label">{{ __('E-mail Address') }}</label>
                            <input type="email" name="email" placeholder="{{ $user->email }}" class="form-input mt-1">
                        </div>
                        @if (settings()->forward_query)
                            <div class="col-span-6">
                                <label class="form-label">Parameter Passing</label>
                                <p class="font-light text-sm dark:text-dark-400">Forward query parameters from your short link to the destination URL. For example, <code class="text-slate-600">https://short.link/abc?utm_medium=social</code> will redirect to <code class="text-slate-600">https://example.com?utm_medium=social</code>.</p>
                                <label class="switch float-right mt-6">
                                    <input type="checkbox" name="forward_query" value="1" {{ $user->forward_query ? 'checked' : '' }}>
                                    <span class="slider"></span>
                                </label>
                            </div>
                        @else
                            <input type="hidden" name="forward_query" value="{{ $user->forward_query ? true : false }}">
                        @endif

                        <div class="col-span-6 lg:col-span-4">
                            <label class="form-label">{{ __('Timezone') }}</label>
                            <p class="font-light text-sm dark:text-dark-400">
                                Choose either a city in the same timezone as you or a UTC (Coordinated Universal Time) time offset.
                            </p>

                            <div class="mt-4">{!! $timezoneList !!}</div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-8 text-right">
                        <button type="submit" class="btn btn-primary btn-sm">
                            {{ __('Save') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

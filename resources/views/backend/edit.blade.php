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

        @if ($url->isExpired())
            <div role="alert" class="card relative mb-4 scroll-mt-7 py-3.5 pl-6.5 pr-4 dark:shadow-xs shadow-orange-600">
                <div class="absolute inset-y-2 left-2 w-0.5 rounded-full bg-orange-600"></div>
                <p class="mb-2 flex items-center gap-x-2 text-orange-600">
                    @svg('icon-sign-warning', '!size-5') <span class="text-xs/4 font-medium">Warning</span>
                </p>
                <p class="text-slate-600 dark:text-dark-400">
                    This link has expired and
                    @if ($url->expired_url)
                        visitors will be redirected to <a href="{{ $url->expired_url }}" class="text-orange-600 hover:underline" target="_blank" rel="noopener noreferrer">{{ urlDisplay($url->expired_url, 90) }}</a>.
                    @else
                        visitors can't access it.
                    @endif
                </p>
            </div>
        @endif

        <form method="post" action="{{ route('link.update', $url) }}">
        @csrf
            <div class="content-container card card-fluid">
                <div class="grid grid-cols-6 gap-6">
                    <div class="col-span-6 lg:col-span-4">
                        <label class="form-label">{{ __('Short URL') }}</label>
                        <span class="text-primary-700 dark:text-emerald-500">{{ urlDisplay($url->short_url, scheme: false) }}</span>
                    </div>

                    <div class="col-span-6">
                        <label class="form-label">{{ __('Title') }}</label>
                        <input name="title" required placeholder="{{ __('Title') }}" value="{{ $url->title }}" class="form-input">
                    </div>

                    <div class="col-span-6">
                        <label class="form-label">{{ __('Destination URL') }}</label>
                        <input name="long_url" required placeholder="http://www.my_long_url.com" value="{{ $url->destination }}" class="form-input">
                    </div>

                    <div class="col-span-6">
                        <label class="form-label">{{ __('Android Link') }}</label>
                        <p class="font-light text-sm dark:text-dark-400 mb-2">Android devices will be automatically redirected to this link.</p>
                        <input name="dest_android" placeholder="https://play.google.com/store/apps/details?id=com.canva.editor" value="{{ $url->dest_android }}" class="form-input">
                    </div>

                    <div class="col-span-6">
                        <label class="form-label">{{ __('iOS Link') }}</label>
                        <p class="font-light text-sm dark:text-dark-400 mb-2">iOS devices will be automatically redirected to this link.</p>
                        <input name="dest_ios" placeholder="https://apps.apple.com/us/app/canva-ai-photo-video-editor/id897446215" value="{{ $url->dest_ios }}" class="form-input">
                    </div>

                    <div class="col-span-6">
                        <label class="form-label">Password</label>
                        <p class="font-light text-sm dark:text-dark-400 mb-2">Protect your link with a password.</p>
                        @if($url->password)
                            <a href="{{ route('link.password.edit', $url) }}" class="btn btn-sm" title="Edit Password">
                                @svg('icon-key', 'mr-1') Edit Password
                            </a>

                            <a href="{{ route('link.password.delete', $url) }}" class="btn btn-delete-danger btn-sm" onclick="return confirm('Are you sure you want to remove the password?')">
                                Remove Password
                            </a>
                        @else
                            <a href="{{ route('link.password.create', $url) }}" class="btn btn-success btn-sm" title="Add Password">
                                @svg('icon-key', 'mr-1') Add Password
                            </a>
                        @endif
                    </div>

                    <div class="col-span-6">
                        <label class="form-label">{{ __('Expiration') }}</label>
                        <p class="font-light text-sm dark:text-dark-400 mt-2 mb-2">Set the expiration date or limit number of clicks to create a temporary short link. The link will become invalid once either criterion is met. Afterwards, it will be either disabled or redirected to the given URL.</p>

                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <p class="font-light text-sm dark:text-dark-400">Link expiration date (UTC)</p>
                                <x-flat-pickr name="expires_at" value="{{ $url->expires_at }}"
                                    :options="['time_24hr' => true, 'disableMobile' => true]"
                                    class="form-input"
                                />
                            </div>

                            <div>
                                <p class="font-light text-sm dark:text-dark-400">Click limit</p>
                                <input name="expired_clicks" placeholder="0" value="{{ $url->expired_clicks }}" class="form-input">
                            </div>
                        </div>

                        <label class="form-label !m-[0.5rem_0_0]">Expiration URL</label>
                        <p class="font-light text-sm dark:text-dark-400">Visitors will be redirected her after the link expires.</p>
                        <input name="expired_url" placeholder="https://example.com/" value="{{ $url->expired_url }}" class="form-input">

                        <label class="form-label !m-[0.5rem_0_0]">Expiration Notes</label>
                        <p class="font-light text-sm dark:text-dark-400">Notes for users who visit your expired link.</p>
                        <textarea name="expired_notes" placeholder="Expired notes" class="form-input">{{ $url->expired_notes }}</textarea>
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

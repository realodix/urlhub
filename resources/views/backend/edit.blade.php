@extends('layouts.backend')

@section('title', 'Edit "'.$url->keyword.'" ‹ '.str()->title(auth()->user()->name))

@section('content')
<div class="container-alt max-w-340 flex flex-wrap">
    <div class="md:w-3/12 flex justify-between">
        <div class="px-4 sm:px-0">
            <h3>Edit URL Details</h3>
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

            <div class="inline sm:block text-sm text-red-600 dark:text-red-500 mt-4">
                @svg('icon-trash', 'mr-1')
                <a role="button" href="{{ route('link.delete', $url) }}" title="Delete"
                    onclick="return confirm('Are you sure you want to delete this link?');"
                >
                    Delete
                </a>
            </div>
        </div>
    </div>
    <div class="w-full md:w-8/12 lg:w-6/12 mt-5 md:mt-0 md:ml-4">
        @include('partials.messages')

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
            <div class="content-container card card-master">
                <div class="grid grid-cols-6 gap-6">
                    <div class="col-span-6">
                        <label class="form-label">Short URL</label>
                        <div class="grid grid-cols-2">
                            <div>
                                @svg('open-link-in-new')
                                <span class="text-primary-700 dark:text-emerald-500">
                                    <a href="{{ $url->short_url }}" target="_blank">{{ urlDisplay($url->short_url, scheme: false) }}</a>
                                </span>
                            </div>
                            <div>
                                @svg('icon-item-detail')
                                <span class="text-primary-700 dark:text-emerald-500">
                                    <a href="{{ route('link_detail', $url) }}" target="_blank">{{ urlDisplay(route('link_detail', $url), scheme: false) }}</a>
                                </span>
                            </div>
                        </div>
                    </div>

                    @if (settings()->autofill_link_title)
                    <div class="col-span-6">
                        <label class="form-label">Title</label>
                        <input name="title" required placeholder="Title" value="{{ $url->title }}" class="form-input">
                    </div>
                    @endif

                    <div class="col-span-6">
                        <label class="form-label">Destination URL</label>
                        <input name="long_url" required placeholder="http://www.my_long_url.com" value="{{ $url->destination }}" class="form-input">
                    </div>

                    <div class="col-span-6">
                        <label class="form-label">Android Link</label>
                        <p class="font-light text-sm dark:text-dark-400 mb-2">Android devices will be automatically redirected to this link.</p>
                        <input name="dest_android" placeholder="https://play.google.com/store/apps" value="{{ $url->dest_android }}" class="form-input">
                    </div>

                    <div class="col-span-6">
                        <label class="form-label">iOS Link</label>
                        <p class="font-light text-sm dark:text-dark-400 mb-2">iOS devices will be automatically redirected to this link.</p>
                        <input name="dest_ios" placeholder="https://apps.apple.com/us/charts/iphone" value="{{ $url->dest_ios }}" class="form-input">
                    </div>

                    <!-- Accordion Container -->
                    @php
                        $advOptSessionId = 'linkOpts-'.substr(session()->getId(), 0, 10).$url->keyword;
                    @endphp
                    <div x-data="{ open: sessionStorage.getItem('{{ $advOptSessionId }}') === 'true' }"
                        x-init="$watch('open', val => sessionStorage.setItem('{{ $advOptSessionId }}', val))"
                        class="col-span-6"
                    >
                        <!-- Accordion Header -->
                        <button type="button" @click="open = !open" class="cursor-pointer flex items-center justify-between w-full px-4 py-2 text-sm font-medium text-left text-gray-500 bg-gray-100 rounded-md dark:bg-dark-800 dark:text-gray-400 hover:bg-dark-200 dark:hover:bg-dark-700 focus:outline-none focus-visible:ring focus-visible:ring-purple-500 focus-visible:ring-opacity-75">
                            <span>Advanced Options</span>
                            <svg x-show="!open" class="w-5 h-5 transition-transform transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                            <svg x-show="open" class="w-5 h-5 transition-transform transform rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Accordion Content -->
                        <div x-show="open" x-collapse class="mt-2">
                            <!-- Password -->
                            <div class="col-span-6">
                                <label class="form-label"># Password</label>
                                <p class="font-light text-sm dark:text-dark-400 mb-2">Protect your link with a password.</p>
                                @if($url->password)
                                    <button type="button" title="Add Password" x-on:click="$dispatch('open-modal', 'edit-password-modal')" class="btn btn-sm">
                                        @svg('icon-key', 'mr-1') Edit Password
                                    </button>

                                    <a href="{{ route('link.password.delete', $url) }}" onclick="return confirm('Are you sure you want to remove the password?')" class="btn btn-delete-danger btn-sm dark:!text-red-700 dark:hover:!text-red-400 dark:!border-red-900">
                                        Remove Password
                                    </a>
                                @else
                                    <button type="button" title="Add Password" x-on:click="$dispatch('open-modal', 'add-password-modal')" class="btn btn-sm">
                                        @svg('icon-key', 'mr-1') Add Password
                                    </button>
                                @endif
                            </div>

                            <!-- Expiration -->
                            <div class="col-span-6 mt-6">
                                <label class="form-label"># Expiration</label>
                                <p class="font-light text-sm dark:text-dark-400 mt-2 mb-2">Set the expiration date or limit number of clicks to create a temporary short link. The link will become invalid once either criterion is met. Afterwards, it will be either disabled or redirected to the given URL.</p>

                                <div class="grid md:grid-cols-2 gap-4">
                                    <div>
                                        <p class="font-light text-sm dark:text-dark-400">Link expiration date (UTC)</p>
                                        <x-flat-pickr name="expires_at" value="{{ $url->expires_at }}"
                                            :options="['time_24hr' => true, 'disableMobile' => true]"
                                            class="flatpickr-input form-input"
                                        />
                                    </div>

                                    <div>
                                        <p class="font-light text-sm dark:text-dark-400">Click limit</p>
                                        <input name="expired_clicks" type="number" placeholder="0" value="{{ $url->expired_clicks }}" class="form-input">
                                    </div>
                                </div>

                                <label class="form-label !m-[0.5rem_0_0]">Expiration URL</label>
                                <p class="font-light text-sm dark:text-dark-400">Visitors will be redirected her after the link expires.</p>
                                <input name="expired_url" placeholder="https://example.com/" value="{{ $url->expired_url }}" class="form-input">

                                <label class="form-label !m-[0.5rem_0_0]">Expiration Notes</label>
                                <p class="font-light text-sm dark:text-dark-400">Notes for users who visit your expired link.</p>
                                <textarea name="expired_notes" placeholder="Expired notes" class="form-input">{{ $url->expired_notes }}</textarea>
                            </div>

                            <!-- Parameter Passing -->
                            @if (settings()->forward_query && $url->author->forward_query)
                                <div class="col-span-6 mt-6">
                                    <label class="form-label"># Parameter Passing</label>
                                    <p class="font-light text-sm dark:text-dark-400">Forward query parameters from your short link to the destination URL. For example, <code class="text-slate-600 dark:text-dark-400 dark:underline dark:decoration-dotted">https://short.link/abc?utm_medium=social</code> will redirect to <code class="text-slate-600 dark:text-dark-400 dark:underline dark:decoration-dotted">https://example.com?utm_medium=social</code>.</p>
                                    <label class="switch float-right mt-6">
                                        <input type="checkbox" name="forward_query" value="1" {{ $url->forward_query ? 'checked' : '' }}>
                                        <span class="slider"></span>
                                    </label>
                                </div>
                            @else
                                <input type="hidden" name="forward_query" value="{{ $url->forward_query ? true : false }}">
                            @endif
                        </div>
                    </div>
                    <!-- End Accordion Container -->
                </div>

                <div class="flex items-center justify-end mt-8 text-right">
                    <button type="submit" class="btn btn-primary btn-sm">
                        Save Changes
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Password Modal
        Because this is a form, it must be placed outside the main form --}}
    @if(!$url->password)
        @include('backend.linkpassword.create')
    @else
        @include('backend.linkpassword.edit')
    @endif
</div>
@endsection

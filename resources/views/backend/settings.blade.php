@extends('layouts.backend')

@section('title', 'Settings')
@section('content')
<div class="container-alt">
    <div class="max-w-4xl ml-0 lg:ml-12">
        @include('partials.messages')

        <div class="content-container card card-master">
            <h1>Settings</h1>

            <form method="post" action="{{ route('dboard.settings.update') }}" class="space-y-6">
            @csrf
                <div class="grid grid-cols-6 gap-6">
                    <div class="col-span-6 lg:col-span-5">
                        <label class="form-label">Public Shortening</label>
                        <div class="font-light text-sm dark:text-dark-400">Allow non-registered users to create new short URLs. If this is disabled, only logged-in users can use the shortener.</div>
                        <label class="switch float-right mt-6">
                            <input type="checkbox" name="public_shortening" value="1" {{ $settings->public_shortening ? 'checked' : '' }}>
                            <span class="slider"></span>
                        </label>
                    </div>
                    <div class="col-span-6 lg:col-span-5">
                        <label class="form-label">Public Registration</label>
                        <div class="font-light text-sm dark:text-dark-400">Allow new users to register an account. If disabled, new accounts can only be created by an administrator.</div>
                        <label class="switch float-right mt-6">
                            <input type="checkbox" name="public_registration" value="1" {{ $settings->public_registration ? 'checked' : '' }}>
                            <span class="slider"></span>
                        </label>
                    </div>

                    <hr class="col-span-6 lg:col-span-5">
                    <h3 class="col-span-6 lg:col-span-5">Short URL</h3>

                    <div class="col-span-6 lg:col-span-5">
                        <label class="form-label">Keyword Length</label>
                        <div class="font-light text-sm dark:text-dark-400">
                            Specify the number of characters to be used in the generated short URL keywords.
                            <br> Expected: {{ $settings::KEY_LEN_LOW }} - {{ $settings::KEY_LEN_UP }}
                        </div>
                        <input name="keyword_length" type="number" required value="{{ $settings->key_len }}"
                            class="form-input mt-4 md:mt-3 max-w-100 @error('keyword_length') border-red-300! @enderror">
                    </div>
                    <div class="col-span-6 lg:col-span-5">
                        <label class="form-label">Min. Custom Keyword Length</label>
                        <div class="font-light text-sm dark:text-dark-400">
                            Specify the minimum number of characters allowed for the custom keyword.
                            <br> Expected: {{ $settings::CST_KEY_MIN_LEN_LOW }} - {{ $settings::CST_KEY_MIN_LEN_UP }}
                        </div>
                        <input name="custom_keyword_min_length" type="number" required value="{{ $settings->cst_key_min_len }}"
                            class="form-input mt-4 md:mt-3 max-w-100 @error('custom_keyword_min_length') border-red-300! @enderror">
                    </div>
                    <div class="col-span-6 lg:col-span-5">
                        <label class="form-label">Max. Custom Keyword Length</label>
                        <div class="font-light text-sm dark:text-dark-400">
                            Specify the maximum number of characters allowed for the custom keyword.
                            <br> Expected: {{ $settings::CST_KEY_MAX_LEN_LOW }} - {{ $settings::CST_KEY_MAX_LEN_UP }}
                        </div>
                        <input name="custom_keyword_max_length" type="number" required value="{{ $settings->cst_key_max_len }}"
                            class="form-input mt-4 md:mt-3 max-w-100 @error('custom_keyword_max_length') border-red-300! @enderror">
                    </div>
                    <div class="col-span-6 lg:col-span-5">
                        <label class="form-label">Autofill Link Title</label>
                        <div class="font-light text-sm dark:text-dark-400">The title is filled by retrieving the website title when creating a short link.</div>
                        <label class="switch float-right mt-6">
                            <input type="checkbox" name="autofill_link_title" value="1" {{ $settings->autofill_link_title ? 'checked' : '' }}>
                            <span class="slider"></span>
                        </label>
                    </div>
                    <div class="col-span-6 lg:col-span-5">
                        <label class="form-label">Favicon Provider</label>
                        <div class="font-light text-sm dark:text-dark-400">Select the favicon provider to use.</div>
                        <select name="favicon_provider" class="form-input max-w-100 mt-6">
                            <option value="duckduckgo" @selected($settings->favicon_provider == 'duckduckgo')>DuckDuckGo</option>
                            <option value="google" @selected($settings->favicon_provider == 'google')>Google</option>
                        </select>
                    </div>

                    <hr class="col-span-6 lg:col-span-5">
                    <h3 class="col-span-6 lg:col-span-5">Redirection</h3>

                    <div class="col-span-6 lg:col-span-5">
                        <label class="form-label">Forward Query Parameters</label>
                        <div class="font-light text-sm dark:text-dark-400">Forward query parameters from your short link to the destination URL. For example, <code class="text-slate-600 dark:text-dark-400 dark:underline dark:decoration-dotted">https://short.link/abc?utm_medium=social</code> will redirect to <code class="text-slate-600 dark:text-dark-400 dark:underline dark:decoration-dotted">https://example.com?utm_medium=social</code>.</div>
                        <label class="switch float-right mt-6">
                            <input type="checkbox" name="forward_query" value="1" {{ $settings->forward_query ? 'checked' : '' }}>
                            <span class="slider"></span>
                        </label>
                    </div>
                    <div class="col-span-6 lg:col-span-5">
                        <label class="form-label inline!">Redirect Cache Lifetime (max-age)</label>
                        <a href="https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Cache-Control#max-age" target="_blank">
                            @svg('icon-help', 'ml-1 hover:scale-110 text-gray-500 dark:text-amber-400')</a>
                        <div class="font-light text-sm dark:text-dark-400">Set the maximum time (in seconds) that a redirect can be cached by a browser. Set to 0 to prevent caching.</div>
                        <input name="redirect_cache_max_age" type="number" required value="{{ $settings->redirect_cache_max_age }}"
                            class="form-input mt-4 md:mt-3 max-w-100 @error('redirect_cache_max_age') border-red-300! @enderror">
                    </div>
                    <div class="col-span-6 lg:col-span-5">
                        <label class="form-label">Track bot visits</label>
                        <div class="font-light text-sm dark:text-dark-400">When enabled, visits from web crawlers and bots will be recorded in the database and included in your link statistics. If disabled, these visits will be ignored and not stored.</div>
                        <label class="switch float-right mt-6">
                            <input type="checkbox" name="track_bot_visits" value="1" {{ $settings->track_bot_visits ? 'checked' : '' }}>
                            <span class="slider"></span>
                        </label>
                    </div>
                </div>

                <hr class="col-span-6 lg:col-span-5">

                <div class="mt-6! mb-4! flex justify-end">
                    <button type="submit" class="btn btn-primary mt-2">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

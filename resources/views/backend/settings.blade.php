@use('App\Services\KeyGeneratorService')

@extends('layouts.backend')

@section('title', __('Settings'))

@section('content')

<main class="!max-w-4xl">
    @include('partials/messages')
    <div class="content">
        <h1>{{ __('Settings') }}</h1>

        <form method="post" action="{{ route('dboard.settings.update') }}" class="space-y-6">
        @csrf
            <div class="grid grid-cols-6 gap-6">
                <div class="col-span-6 lg:col-span-4">
                    <label class="form-label">Allow Anyone to Shorten URLs</label>
                    <div class="font-light text-sm dark:text-dark-400">Enable to allow anyone to create short URLs. If disabled, only registered users can create them.</div>
                    <label class="switch float-right mt-6">
                        <input type="checkbox" name="anyone_can_shorten" value="1" {{ $settings->anyone_can_shorten ? 'checked' : '' }}>
                        <span class="slider"></span>
                    </label>
                </div>
                <div class="col-span-6 lg:col-span-4">
                    <label class="form-label">Allow User Registration</label>
                    <div class="font-light text-sm dark:text-dark-400">Enable to allow new user registrations. If disabled, no new user registrations are allowed.</div>
                    <label class="switch float-right mt-6">
                        <input type="checkbox" name="anyone_can_register" value="1" {{ $settings->anyone_can_register ? 'checked' : '' }}>
                        <span class="slider"></span>
                    </label>
                </div>

                <hr class="col-span-6 lg:col-span-4">
                <h3 class="col-span-6 lg:col-span-4">Short URL Keyword</h3>

                <div class="col-span-6 lg:col-span-4">
                    <label class="form-label">Keyword Length</label>
                    <div class="font-light text-sm dark:text-dark-400">Specifies the number of characters to be used in the generated short URL keywords. This value must be between 2 and 20.</div>
                    <input name="keyword_length" required value="{{ $settings->keyword_length }}"
                        class="form-input mt-4 md:mt-3 @error('keyword_length') !border-red-300 @enderror">
                </div>
                <div class="col-span-6 lg:col-span-4">
                    <label class="form-label">Min. Custom Keyword Length</label>
                    <div class="font-light text-sm dark:text-dark-400">Specify the minimum number of characters allowed for the custom keyword. This value must be between 2 and 19.</div>
                    <input name="custom_keyword_min_length" required value="{{ $settings->custom_keyword_min_length }}"
                        class="form-input mt-4 md:mt-3 @error('custom_keyword_min_length') !border-red-300 @enderror">
                </div>
                <div class="col-span-6 lg:col-span-4">
                    <label class="form-label">Max. Custom Keyword Length</label>
                    <div class="font-light text-sm dark:text-dark-400">Specify the maximum number of characters allowed for the custom keyword. This value must be between 3 and 20.</div>
                    <input name="custom_keyword_max_length" required value="{{ $settings->custom_keyword_max_length }}"
                        class="form-input mt-4 md:mt-3 @error('custom_keyword_max_length') !border-red-300 @enderror">
                </div>
                <div class="col-span-6 lg:col-span-4">
                    <label class="form-label">Fetch Website Title</label>
                    <div class="font-light text-sm dark:text-dark-400">Automatically retrieves the website's title when creating a short URL. If disabled, the domain name will be used instead.</div>
                    <label class="switch float-right mt-6">
                        <input type="checkbox" name="retrieve_web_title" value="1" {{ $settings->retrieve_web_title ? 'checked' : '' }}>
                        <span class="slider"></span>
                    </label>
                </div>

                <hr class="col-span-6 lg:col-span-4">
                <h3 class="col-span-6 lg:col-span-4">Redirection</h3>

                <div class="col-span-6 lg:col-span-4">
                    <label class="form-label !inline">Redirection Status Code</label>
                    <a href="https://developer.mozilla.org/en-US/docs/Web/HTTP/Redirections" target="_blank">@svg('icon-help', 'ml-1 hover:scale-110')</a>
                    <div class="font-light text-sm dark:text-dark-400">The HTTP status code to use when redirecting a visitor to the original URL.</div>
                    <select name="redirect_status_code" class="form-input mt-4 md:mt-3">
                        <option value="301" {{ $settings->redirect_status_code == 301 ? 'selected' : '' }}>301 - Permanent Redirect</option>
                        <option value="302" {{ $settings->redirect_status_code == 302 ? 'selected' : '' }}>302 - Temporary Redirect</option>
                    </select>
                </div>
                <div class="col-span-6 lg:col-span-4">
                    <label class="form-label !inline">HTTP Cache-Control header (max-age)</label>
                    <a href="https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Cache-Control#max-age" target="_blank">@svg('icon-help', 'ml-1 hover:scale-110')</a>
                    <div class="font-light text-sm dark:text-dark-400">Set the maximum age for the HTTP Cache-Control header in seconds. Set to 0 for no caching.</div>
                    <input name="redirect_cache_max_age" required value="{{ $settings->redirect_cache_max_age }}"
                        class="form-input mt-4 md:mt-3 @error('redirect_cache_max_age') !border-red-300 @enderror">
                </div>
                <div class="col-span-6 lg:col-span-4">
                    <label class="form-label">{{ __('Track bot visits') }}</label>
                    <div class="font-light text-sm dark:text-dark-400">Determine whether bot visits count as visitors or not. If enabled, bot visits will be counted as visitors.</div>
                    <label class="switch float-right mt-6">
                        <input type="checkbox" name="track_bot_visits" value="1" {{ $settings->track_bot_visits ? 'checked' : '' }}>
                        <span class="slider"></span>
                    </label>
                </div>
            </div>

            <hr class="col-span-6 lg:col-span-4">

            <div class="!mt-6 !mb-4 flex justify-end">
                <button type="submit" class="btn btn-primary btn-sm mt-2">
                    {{ __('Save Changes') }}
                </button>
            </div>
        </form>
    </div>
</main>
@endsection

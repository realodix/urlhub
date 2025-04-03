@extends('layouts.backend')

@section('title', __('About System'))
@section('content')
<div class="page_about container-alt max-w-4xl">
    @php
        $debug = config('app.debug');
        $env = (string) app()->environment();
    @endphp

    <div class="mb-6">
        @if ($debug == true || $env !== 'production')
        <div role="alert" class="card relative mb-4 scroll-mt-7 py-3.5 pl-6.5 pr-4 dark:shadow-xs shadow-orange-600">
            <div class="absolute inset-y-2 left-2 w-0.5 rounded-full bg-orange-600"></div>
            <p class="mb-2 flex items-center gap-x-2 text-orange-600">
                @svg('icon-sign-warning', '!size-5')
                <span class="text-xs/4 font-medium">Warning</span>
            </p>
            <ul class="text-slate-600 dark:text-dark-400">
                @if ($env !== 'production')
                    <li>The environment was expected to be <span class="env_value env_value_expected">production</span>, but actually was <span class="env_value env_value_actual">{{ $env }}</span>.</li>
                @endif
                @if ($debug === true)
                    <li>The debug mode was expected to be <span class="env_value env_value_expected">false</span>, but actually was <span class="env_value  env_value_actual">true</span>.</li>
                @endif
            </ul>
        </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="card card-fluid shadow-xs p-4 md:col-span-2">
                <p class="text-uh-logo dark:text-uh-logo-dark text-sm font-medium leading-4">UrlHub</p>
                <p class="text-2xl font-bold text-slate-700 dark:text-dark-300">
                    {{ config('urlhub.app_version') }}
                </p>
            </div>
            <div class="card card-fluid shadow-xs p-4">
                <p class="text-[oklch(48.68%_0.0912_273.4)] dark:text-[oklch(62.3%_0.0912_273.4)] text-sm font-medium leading-4">PHP</p>
                <p class="text-2xl font-bold text-slate-700 dark:text-dark-300">
                    <a href="https://www.php.net/ChangeLog-8.php#{{ phpversion() }}" target="_blank">
                        {{ phpversion() }}
                    </a>
                </p>
            </div>
            <div class="card card-fluid shadow-xs p-4">
                <p class="text-[#ff2d20] text-sm font-medium leading-4">Laravel</p>
                <p class="text-2xl font-bold text-slate-700 dark:text-dark-300">
                    <a href="https://github.com/laravel/framework/releases/tag/v{{ app()->version() }}" target="_blank">
                        {{ app()->version() }}
                    </a>
                </p>
            </div>
        </div>
    </div>

    @php
        $domainBlacklist = collect(config('urlhub.domain_blacklist'))->sort();
        $reservedActiveKeyList = $keyGenService->reservedActiveKeyword()->toArray();
        $reservedKeyword = $keyGenService->reservedKeyword();
    @endphp
    <div class="config content-container card card-fluid">
        <h1>
            {{ __('Configuration') }}
            <p class="font-light text-sm float-right"><span class="text-gray-500/85">.\config</span>\urlhub.php</p>
        </h1>

        <h3>Shortened Links</h3>
        <dl>
            <dt class="mt-2">
                <code>domain_blacklist</code>
                <p class="font-light text-sm dark:text-dark-400">This is a list of domain names that are not allowed to be shortened.</p>
            </dt>
            <dd class="mt-2">
                <div class="card !bg-gray-50 dark:!bg-dark-950/50 !rounded px-3 py-2 text-sm">
                    @if ($domainBlacklist->isNotEmpty())
                        <code>{{ $domainBlacklist->implode(', ') }}</code>
                    @else
                        <code>None</code>
                    @endif
                </div>
            </dd>

            <dt class="mt-2 mb-2">
                <code>reserved_keyword</code>
                <p class="font-light text-sm dark:text-dark-400">
                    Reserved keywords are strings that cannot be used as a shortened URL keyword. The route list and folder/file names in the public folder are also included in this list.
                </p>
            </dt>
            <dd class="mt-2 mb-2">
                <div class="card !bg-gray-50 dark:!bg-dark-950/50 !rounded px-3 py-2 text-sm">
                    <code class="text-slate-500 dark:text-dark-600">// {{ $reservedKeyword->count() }} Strings</code> <br>
                    @foreach ($reservedKeyword as $reservedKeywordItem)
                        @php $separator = $loop->last ? '.' : ','; @endphp
                        <code class="dark:text-dark-400">{{ $reservedKeywordItem }}</code>
                        {{$separator}}
                    @endforeach

                    @if (!empty($reservedActiveKeyList))
                        <br><br>
                        <code class="text-red-400 dark:text-orange-600">// Unfortunately the list below is already used </code> <br>
                        <code class="text-red-400 dark:text-orange-600">// as shortened URL keyword</code> <br>

                        @foreach ($reservedActiveKeyList as $reservedActiveKey)
                            @php $separator = $loop->last ? '.' : ','; @endphp
                            <code><a href="{{ route('link_detail', $reservedActiveKey) }}" target="_blank"
                                class="underline decoration-dotted">
                                {{ $reservedActiveKey }}</a></code>
                            {{$separator}}
                        @endforeach
                    @endif
                </div>
            </dd>

            <dt class="mt-2">
                <code>redirection_status_code</code>
                <p class="font-light text-sm dark:text-dark-400">
                    The HTTP status code to use when redirecting a visitor to the original URL.
                </p>
            </dt>
            <dd class="mt-2">
                {{ config('urlhub.redirection_status_code') }}
            </dd>
        </dl>
    </div>
</div>
@endsection

@extends('layouts.backend')

@section('title', 'About')
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
                @svg('icon-sign-warning', 'size-5!')
                <span class="text-xs/4 font-medium">Warning</span>
            </p>
            <ul class="text-slate-600 dark:text-dark-400">
                @if ($env !== 'production')
                    <li>The environment was expected to be <span class="env_value env_value_expected">production</span>, but actually was <span class="env_value env_value_actual">{{ $env }}</span>.</li>
                @endif
                @if ($debug === true)
                    <li>The debug mode was expected to be <span class="env_value env_value_expected">false</span>, but actually was <span class="env_value env_value_actual">true</span>.</li>
                @endif
            </ul>
        </div>
        @endif

        <div class="card card-master overflow-hidden rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-dark-900 shadow-xs">
            <div class="grid grid-cols-1 divide-y divide-slate-200/80 dark:divide-dark-800 md:grid-cols-[1.5fr_1fr_1fr] md:divide-x md:divide-y-0">
                <div class="flex items-center justify-between p-4 sm:px-6">
                    <div>
                        <p class="text-xs font-medium text-slate-500 dark:text-slate-400">UrlHub</p>
                        <p class="font-mono text-lg font-bold tracking-tight text-slate-800 dark:text-slate-100">
                            {{ config('urlhub.app_version') }}
                        </p>
                    </div>

                    @php
                        $status = 'stable';
                        if (str_ends_with(config('urlhub.app_version'), '-dev')) {
                            $status = 'dev';
                        }
                    @endphp

                    @if ($status === 'stable')
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-500/10 px-2.5 py-1 text-xs font-medium text-emerald-600 dark:text-emerald-400 ring-1 ring-inset ring-emerald-500/20">
                            <span class="size-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            {{$status}}
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-500/10 px-2.5 py-1 text-xs font-medium text-amber-600 dark:text-amber-400 ring-1 ring-inset ring-amber-500/20">
                            <span class="size-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                            {{$status}}
                        </span>
                    @endif
                </div>

                <a href="https://www.php.net/ChangeLog-8.php#{{ phpversion() }}" target="_blank" class="group flex items-center gap-3.5 p-4 sm:px-6 transition-colors hover:bg-linear-to-br hover:from-white hover:via-white hover:to-indigo-50/40 dark:hover:from-dark-900 dark:hover:via-dark-900 dark:hover:to-indigo-950/20">
                    <span class="relative isolate flex shrink-0 items-center justify-center overflow-hidden bg-linear-to-b shadow-[inset_0_1px_0_rgb(255_255_255/0.9)] ring-1 ring-slate-200/80 ring-inset dark:ring-dark-700 dark:shadow-[inset_0_1px_0_rgb(255_255_255/0.06)] size-10 rounded-xl">
                        <span aria-hidden="true" class="absolute inset-0 -z-10 bg-radial-[circle_at_50%_75%] from-[#777bb4]/22 to-transparent to-70%"></span>
                        <div class="text-[#777bb4]">
                            <svg role="img" viewBox="0 0 24 24" class="size-6" aria-label="PHP">
                                <path d="M7.01 10.207h-.944l-.515 2.648h.838c.556 0 .97-.105 1.242-.314.272-.21.455-.559.55-1.049.092-.47.05-.802-.124-.995-.175-.193-.523-.29-1.047-.29zM12 5.688C5.373 5.688 0 8.514 0 12s5.373 6.313 12 6.313S24 15.486 24 12c0-3.486-5.373-6.312-12-6.312zm-3.26 7.451c-.261.25-.575.438-.917.551-.336.108-.765.164-1.285.164H5.357l-.327 1.681H3.652l1.23-6.326h2.65c.797 0 1.378.209 1.744.628.366.418.476 1.002.33 1.752a2.836 2.836 0 0 1-.305.847c-.143.255-.33.49-.561.703zm4.024.715l.543-2.799c.063-.318.039-.536-.068-.651-.107-.116-.336-.174-.687-.174H11.46l-.704 3.625H9.388l1.23-6.327h1.367l-.327 1.682h1.218c.767 0 1.295.134 1.586.401s.378.7.263 1.299l-.572 2.944h-1.389zm7.597-2.265a2.782 2.782 0 0 1-.305.847c-.143.255-.33.49-.561.703a2.44 2.44 0 0 1-.917.551c-.336.108-.765.164-1.286.164h-1.18l-.327 1.682h-1.378l1.23-6.326h2.649c.797 0 1.378.209 1.744.628.366.417.477 1.001.331 1.751zM17.766 10.207h-.943l-.516 2.648h.838c.557 0 .971-.105 1.242-.314.272-.21.455-.559.551-1.049.092-.47.049-.802-.125-.995s-.524-.29-1.047-.29z" fill="currentColor"/>
                            </svg>
                        </div>
                    </span>
                    <div>
                        <p class="text-xs text-slate-500 dark:text-slate-400">PHP</p>
                        <p class="text-sm font-semibold text-slate-800 dark:text-slate-200 group-hover:text-[#4f5b93] group-hover:dark:text-[#7583be] transition-colors">
                            {{ phpversion() }} <span class="size-4 shrink-0 text-slate-400 opacity-0 -translate-x-1 translate-y-1 transition-all duration-400 group-hover:opacity-100 group-hover:translate-x-0 group-hover:translate-y-0 group-hover:text-[#4f5b93] group-hover:dark:text-[#7583be]">↗</span>
                        </p>
                    </div>
                </a>

                <a href="https://github.com/laravel/framework/releases/tag/v{{ app()->version() }}" target="_blank" class="group flex items-center gap-3.5 p-4 sm:px-6 transition-colors hover:bg-linear-to-br hover:from-white hover:via-white hover:to-amber-50/40 dark:hover:from-dark-900 dark:hover:via-dark-900 dark:hover:to-amber-950/20">
                    <span class="relative isolate flex shrink-0 items-center justify-center overflow-hidden bg-linear-to-b shadow-[inset_0_1px_0_rgb(255_255_255/0.9)] ring-1 ring-slate-200/80 ring-inset dark:ring-dark-700 dark:shadow-[inset_0_1px_0_rgb(255_255_255/0.06)] size-10 rounded-xl">
                        <span aria-hidden="true" class="absolute inset-0 -z-10 bg-radial-[circle_at_50%_75%] from-[#f97556]/22 to-transparent to-70%"></span>
                        <div class="text-[#f53003]">
                            <svg role="img" viewBox="0 0 24 24" class="size-5" aria-label="Laravel">
                                <path fill="currentColor" d="M23.642 5.43a.364.364 0 01.014.1v5.149c0 .135-.073.26-.189.326l-4.323 2.49v4.934a.378.378 0 01-.188.326L9.93 23.949a.316.316 0 01-.066.027c-.008.002-.016.008-.024.01a.348.348 0 01-.192 0c-.011-.002-.02-.008-.03-.012-.02-.008-.042-.014-.062-.025L.533 18.755a.376.376 0 01-.189-.326V2.974c0-.033.005-.066.014-.098.003-.012.01-.02.014-.032a.369.369 0 01.023-.058c.004-.013.015-.022.023-.033l.033-.045c.012-.01.025-.018.037-.027.014-.012.027-.024.041-.034H.53L5.043.05a.375.375 0 01.375 0L9.93 2.647h.002c.015.01.027.021.04.033l.038.027c.013.014.02.03.033.045.008.011.02.021.025.033.01.02.017.038.024.058.003.011.01.021.013.032.01.031.014.064.014.098v9.652l3.76-2.164V5.527c0-.033.004-.066.013-.098.003-.01.01-.02.013-.032a.487.487 0 01.024-.059c.007-.012.018-.02.025-.033.012-.015.021-.03.033-.043.012-.012.025-.02.037-.028.014-.01.026-.023.041-.032h.001l4.513-2.598a.375.375 0 01.375 0l4.513 2.598c.016.01.027.021.042.031.012.01.025.018.036.028.013.014.022.03.034.044.008.012.019.021.024.033.011.02.018.04.024.06.006.01.012.021.015.032zm-.74 5.032V6.179l-1.578.908-2.182 1.256v4.283zm-4.51 7.75v-4.287l-2.147 1.225-6.126 3.498v4.325zM1.093 3.624v14.588l8.273 4.761v-4.325l-4.322-2.445-.002-.003H5.04c-.014-.01-.025-.021-.04-.031-.011-.01-.024-.018-.035-.027l-.001-.002c-.013-.012-.021-.025-.031-.04-.01-.011-.021-.022-.028-.036h-.002c-.008-.014-.013-.031-.02-.047-.006-.016-.014-.027-.018-.043a.49.49 0 01-.008-.057c-.002-.014-.006-.027-.006-.041V5.789l-2.18-1.257zM5.23.81L1.47 2.974l3.76 2.164 3.758-2.164zm1.956 13.505l2.182-1.256V3.624l-1.58.91-2.182 1.255v9.435zm11.581-10.95l-3.76 2.163 3.76 2.163 3.759-2.164zm-.376 4.978L16.21 7.087 14.63 6.18v4.283l2.182 1.256 1.58.908zm-8.65 9.654l5.514-3.148 2.756-1.572-3.757-2.163-4.323 2.489-3.941 2.27z"/>
                            </svg>
                        </div>
                    </span>
                    <div>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Laravel</p>
                        <p class="text-sm font-semibold text-slate-800 dark:text-slate-200 group-hover:text-[#f53003] transition-colors">
                            {{ app()->version() }} <span class="size-4 shrink-0 text-slate-400 opacity-0 -translate-x-1 translate-y-1 transition-all duration-400 group-hover:opacity-100 group-hover:translate-x-0 group-hover:translate-y-0 group-hover:text-[#f53003]">↗</span>
                        </p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    @php
        $domainBlacklist = collect(config('urlhub.blacklist_domain'))->sort();
        $disallowedKeys = $keyGenService->disallowedKeyword();
        $keywordInUse = $blockedStringService->keywordInUse();
        $domainInUse = $blockedStringService->domainInUse();
    @endphp
    <div class="config content-container card card-master">
        <h1 class="text-2xl">
            Configuration
            <p class="font-light text-sm float-right"><span class="text-gray-500/85">.\config</span>\urlhub.php</p>
        </h1>

        <h3 class="text-xl">Shortened Links</h3>
        <dl>
            <dt class="mt-2">
                <code>redirect_status_code</code>
                <p class="font-light text-sm dark:text-dark-400">
                    The HTTP status code to use when redirecting a visitor to the original URL.
                </p>
            </dt>
            <dd class="mt-2">
                {{ config('urlhub.redirect_status_code') }}
            </dd>

            <dt class="mt-2">
                <code>redirect_cache_lifetime</code>
                <p class="font-light text-sm dark:text-dark-400">
                    The maximum time (in seconds) a redirect response may be cached.
                </p>
            </dt>
            <dd class="mt-2">
                {{ config('urlhub.redirect_cache_lifetime') }}
            </dd>

            <dt class="mt-2 mb-2">
                <p>Disallowed Keywords</p>
                <p class="font-light text-sm dark:text-dark-400">
                    Strings blocked from use in short URL endings due to system reservations, blacklisting, or potential conflicts.
                </p>
            </dt>
            <dd class="mt-2 mb-2">
                <div class="card bg-gray-50! dark:bg-dark-950/50! rounded! px-3 py-2 text-sm">
                    <code class="text-slate-500 dark:text-dark-600">// {{ $disallowedKeys->count() }} Strings</code> <br>
                    @foreach ($disallowedKeys as $disallowedKey)
                        @php $separator = $loop->last ? '.' : ','; @endphp
                        <code class="dark:text-dark-400">{{ $disallowedKey }}</code>
                        {{$separator}}
                    @endforeach

                    @if ($keywordInUse->isNotEmpty())
                        <br><br>
                        <code class="text-red-400 dark:text-orange-600">// Unfortunately the list below is already used </code>
                        <br>
                        @foreach ($keywordInUse as $keyword)
                            @php $separator = $loop->last ? '.' : ','; @endphp
                            <code><a href="{{ route('link_detail', $keyword) }}" target="_blank"
                                class="underline decoration-dotted">
                                {{ $keyword }}</a></code>
                            {{$separator}}
                        @endforeach
                    @endif
                </div>
            </dd>

            <dt class="mt-2">
                <code>blacklist_domain</code>
                <p class="font-light text-sm dark:text-dark-400">This is a list of domain names that are not allowed to be shortened.</p>
            </dt>
            <dd class="mt-2">
                <div class="card bg-gray-50! dark:bg-dark-950/50! rounded! px-3 py-2 text-sm">
                    @if ($domainBlacklist->isNotEmpty())
                        <code>{{ $domainBlacklist->implode(', ') }}</code>

                        @if ($domainInUse->isNotEmpty())
                            <br><br>
                            <code class="text-red-400 dark:text-orange-600">// Unfortunately the list below is already used</code>
                            <br>
                            @foreach ($domainInUse as $domain)
                                <code>
                                    [{{ $domain->keyword }}] {{ $domain->destination }}
                                </code>
                                <br>
                            @endforeach
                        @endif
                    @else
                        <code>None</code>
                    @endif
                </div>
            </dd>
        </dl>
    </div>
</div>
@endsection

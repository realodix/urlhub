@use('App\Services\KeyGeneratorService')

@extends('layouts.backend')

@section('title', __('About System'))
@section('content')
<main class="page_about !max-w-4xl">
    @php
        $debug = config('app.debug');
        $env = (string) app()->environment();
        $appVersion = str(config('urlhub.app_version'));
        $commitVersion = runGitCommand('git rev-parse master');
    @endphp

    <div class="mb-6">
        @if ($debug == true || $env !== 'production')
        <div role="alert" class="card relative mb-4 scroll-mt-7 py-3.5 pl-6.5 pr-4">
            <div class="absolute inset-y-2 left-2 w-0.5 rounded-full bg-orange-600"></div>
            <p class="mb-2 flex items-center gap-x-2 text-orange-600">
                @svg('icon-sign-warning', '!size-5')
                <span class="text-xs/4 font-medium">Warning</span>
            </p>
            <ul class="text-slate-600 dark:text-dark-400">
                @if ($env !== 'production')
                    <li>The environment was expected to be <code class="code">production</code>, but actually was <code class="code">{{ $env }}</code>.</li>
                @endif
                @if ($debug === true)
                    <li>The debug mode was expected to be <code class="code">false</code>, but actually was <code class="code">true</code>.</li>
                @endif
            </ul>
        </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="card card-master shadow-xs p-4 md:col-span-2">
                <p class="text-uh-blue text-sm font-medium leading-4">UrlHub</p>
                <p class="text-2xl font-bold text-slate-700 dark:text-dark-300">
                    @if($appVersion->endsWith('-dev') && !empty($commitVersion))
                        <a href="https://github.com/realodix/urlhub/compare/{{ $commitVersion }}...master" target="_blank">
                            {{$appVersion->remove('dev')}}{{ substr($commitVersion, 0 , 7) }}
                        </a>
                    @else
                        {{ $appVersion->lower() }}
                    @endif
                </p>
            </div>
            <div class="card card-master shadow-xs p-4">
                <p class="text-[#4f5b93] text-sm font-medium leading-4">PHP</p>
                <p class="text-2xl font-bold text-slate-700 dark:text-dark-300">
                    <a href="https://www.php.net/ChangeLog-8.php#{{ phpversion() }}" target="_blank">
                        {{ phpversion() }}
                    </a>
                </p>
            </div>
            <div class="card card-master shadow-xs p-4">
                <p class="text-[#ff2d20] text-sm font-medium leading-4">Laravel</p>
                <p class="text-2xl font-bold text-slate-700 dark:text-dark-300">
                    <a href="https://github.com/laravel/framework/releases/tag/v{{ app()->version() }}" target="_blank">
                        {{ app()->version() }}
                    </a>
                </p>
            </div>
        </div>
    </div>

    <div class="content">
        @php
            $urlCount = n_abb($url->count());
            $visitCount = n_abb($visit->count());
            $userUrlCount = n_abb($url->userUrlCount());
            $userLinkVisitCount = n_abb($visit->userLinkVisitCount());
            $guestUrlCount = n_abb($url->guestUserUrlCount());
            $guestUserLinkVisitCount = n_abb($visit->guestUserLinkVisitCount());
        @endphp

        <h3>Links</h3>

        <div class="mt-4 mb-6 px-0">
            <dl class="grid grid-cols-1 gap-2.5 sm:gap-3 md:gap-5 md:grid-cols-2 lg:grid-cols-3">
                <div class="card !bg-gray-50 dark:!bg-dark-950/50 !rounded px-4 py-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-600 dark:text-dark-400 md:mt-1">
                            Total
                        </dt>
                        <dd class="-mt-1 font-normal text-gray-900 dark:text-dark-300 md:mt-1 md:text-xl">
                            {{ $urlCount }} ({{ $visitCount }} visits)
                        </dd>
                    </div>
                </div>
                <div class="card !bg-gray-50 dark:!bg-dark-950/50 !rounded px-4 py-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-600 dark:text-dark-400 md:mt-1">
                            User
                        </dt>
                        <dd class="-mt-1 font-normal text-gray-900 dark:text-dark-300 md:mt-1 md:text-xl">
                            {{ $userUrlCount }} ({{ $userLinkVisitCount }} visits)
                        </dd>
                    </div>
                </div>
                <div class="card !bg-gray-50 dark:!bg-dark-950/50 !rounded px-4 py-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-600 dark:text-dark-400 md:mt-1">
                            Guest
                        </dt>
                        <dd class="-mt-1 font-normal text-gray-900 dark:text-dark-300 md:mt-1 md:text-xl">
                            {{ $guestUrlCount }} ({{ $guestUserLinkVisitCount }} visits)
                        </dd>
                    </div>
                </div>
            </dl>
        </div>

        <h3>Users</h3>
        <div class="mt-4 mb-6 px-0">
            <dl class="grid grid-cols-1 gap-2.5 sm:gap-3 md:gap-5 md:grid-cols-2 lg:grid-cols-3">
                <div class="card !bg-gray-50 dark:!bg-dark-950/50 !rounded px-4 py-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-600 dark:text-dark-400 md:mt-1">
                            User
                        </dt>
                        <dd class="-mt-1 font-normal text-gray-900 dark:text-dark-300 md:mt-1 md:text-xl">
                            {{ n_abb($user->count()) }}
                        </dd>
                    </div>
                </div>
                <div class="card !bg-gray-50 dark:!bg-dark-950/50 !rounded px-4 py-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-600 dark:text-dark-400 md:mt-1">
                            Guest
                        </dt>
                        <dd class="-mt-1 font-normal text-gray-900 dark:text-dark-300 md:mt-1 md:text-xl">
                            {{ n_abb($user->totalGuestUsers()) }}
                        </dd>
                    </div>
                </div>
            </dl>
        </div>

        <h3>Random String</h3>
        <p class="font-light text-sm dark:text-dark-400">Random String Generation for Shortened URLs.</p>
        <div class="mt-4 mb-6 px-0">
            <dl class="grid grid-cols-1 md:grid-flow-col md:auto-cols-auto gap-2.5 sm:gap-3">
                <div class="card !bg-gray-50 dark:!bg-dark-950/50 !rounded px-4 py-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-600 dark:text-dark-400 md:mt-1">
                            Potential Output
                        </dt>
                        <dd class="-mt-1 font-normal text-gray-900 dark:text-dark-300 md:mt-1 md:text-xl">
                            @if ($keyGenerator->possibleOutput() === PHP_INT_MAX)
                                (<code>PHP_INT_MAX</code>) {{ number_format(PHP_INT_MAX) }}
                            @else
                                @php
                                    $number = strlen(KeyGeneratorService::ALPHABET);
                                    $powNumber = settings()->keyword_length;
                                    $result = number_format($keyGenerator->possibleOutput());
                                @endphp

                                ( {{ $number }}<sup>{{ $powNumber }}</sup> ) {{ $result }}
                            @endif
                        </dd>
                    </div>
                </div>
                <div class="card !bg-gray-50 dark:!bg-dark-950/50 !rounded px-4 py-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-600 dark:text-dark-400 md:mt-1 md:w-64">
                            Generated Count
                        </dt>
                        <dd class="-mt-1 font-normal text-gray-900 dark:text-dark-300 md:mt-1 md:text-xl md:w-64">
                            {{ number_format($keyGenerator->totalKey()) }}
                        </dd>
                    </div>
                </div>
            </dl>
        </div>
    </div>

    <br>

    @php
        $domainBlacklist = collect(config('urlhub.domain_blacklist'))
            ->sort()->toArray();
        $reservedActiveKeyList = $keyGenerator->reservedActiveKeyword()->toArray();
        $reservedKeyword = $keyGenerator->reservedKeyword();
    @endphp
    <div class="content config">
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
                    @if (!empty($domainBlacklist))
                        <code>{{ implode(", ", $domainBlacklist) }}</code>
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
                    <code class="dark:text-dark-400">{{ $reservedKeyword->implode(', ') }}</code>

                    @if (!empty($reservedActiveKeyList))
                        <br><br>
                        <code class="text-red-400 dark:text-orange-500/70">// Unfortunately the list below is already used </code> <br>
                        <code class="text-red-400 dark:text-orange-500/70">// as shortened URL keyword</code> <br>
                        <code>

                        @foreach ($reservedActiveKeyList as $reservedActiveKey)
                            <a href="{{ route('link_detail', $reservedActiveKey) }}"
                                target="_blank"
                                class="underline decoration-dotted">{{ $reservedActiveKey }}</a>,
                        @endforeach
                        </code>
                    @endif
                </div>
            </dd>
        </dl>
    </div>
</main>
@endsection

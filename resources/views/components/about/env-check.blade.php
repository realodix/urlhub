@php
    $debug = config('app.debug');
    $env = (string) app()->environment();
@endphp

<div {{ $attributes }}>
    <div class="mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="--card-style p-4 md:col-start-2">
                <p class="text-[#4f5b93] text-sm font-medium leading-4">PHP</p>
                <p class="text-2xl font-bold text-slate-600">
                    {{ phpversion() }}
                </p>
            </div>
            <div class="--card-style p-4 ">
                <p class="text-[#ff2d20] text-sm font-medium leading-4">Laravel</p>
                <p class="text-2xl font-bold text-slate-600">
                    {{ app()->version() }}
                </p>
            </div>
        </div>
    </div>

    <div class="px-2 md:px-0">
        <div class="grid grid-cols-1 gap-2.5 sm:gap-3 md:gap-5 md:grid-cols-2">
            <div class="flex items-start p-4 space-x-2 md:space-x-3 bg-white border border-border-200 rounded-xl">
                @if ($debug === true)
                    <div class="rounded-full flex items-center justify-center p-0 md:p-2.5 md:bg-red-100">
                        <div class="rounded-full absolute w-3.5 h-3.5 bg-white"></div>
                        @svg('icon-mark-fail', '!h-5 w-5 relative text-red-500')
                    </div>
                @else
                    <div class="rounded-full flex items-center justify-center p-0 md:p-2.5 md:bg-emerald-100">
                        <div class="rounded-full absolute w-3.5 h-3.5 bg-white"></div>
                        @svg('icon-mark-check', '!h-5 w-5 relative text-emerald-500')
                    </div>
                @endif

                <div>
                    <p class="-mt-1 font-bold text-gray-900">
                        Debug Mode
                    </p>
                    <p class="mt-0 text-sm font-light text-gray-600 md:mt-1">
                        @if ($debug === true)
                            The debug mode was expected to be <code class="code">false</code>, but actually was <code class="code">true</code>.
                        @else
                            false
                        @endif
                    </p>
                </div>
            </div>

            <div class="flex items-start p-4 space-x-2 md:space-x-3 bg-white border border-border-200 rounded-xl">
                @if ($env !== 'production')
                    <div class="rounded-full flex items-center justify-center p-0 md:p-2.5 md:bg-red-100">
                        <div class="rounded-full absolute w-3.5 h-3.5 bg-white"></div>
                        @svg('icon-mark-fail', '!h-5 w-5 relative text-red-500')
                    </div>
                @else
                    <div class="rounded-full flex items-center justify-center p-0 md:p-2.5 md:bg-emerald-100">
                        <div class="rounded-full absolute w-3.5 h-3.5 bg-white"></div>
                        @svg('icon-mark-check', '!h-5 w-5 relative text-emerald-500')
                    </div>
                @endif

                <div>
                    <p class="-mt-1 font-bold text-gray-900">
                        Environment
                    </p>
                    <p class="mt-0 text-sm font-light text-gray-600 md:mt-1">
                        @if ($env !== 'production')
                            The environment was expected to be <code class="code">production</code>, but actually was <code class="code">{{ $env }}</code>.
                        @else
                            production
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

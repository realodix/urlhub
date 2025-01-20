@php
    $debug = config('app.debug');
    $env = (string) app()->environment();
@endphp

<div {{ $attributes }}>
    <div class="mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="card card-master shadow-sm p-4 md:col-start-2">
                <p class="text-[#4f5b93] text-sm font-medium leading-4">PHP</p>
                <p class="text-2xl font-bold text-slate-700">
                    {{ phpversion() }}
                </p>
            </div>
            <div class="card card-master shadow-sm p-4">
                <p class="text-[#ff2d20] text-sm font-medium leading-4">Laravel</p>
                <p class="text-2xl font-bold text-slate-700">
                    {{ app()->version() }}
                </p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-2.5 sm:gap-3 md:gap-5 md:grid-cols-2">
        <div class="card card-master flex items-start p-4 space-x-2 md:space-x-3">
            @if ($debug === true)
                <x-about.mark-fail/>
            @else
                <x-about.mark-check/>
            @endif

            <div>
                <p class="-mt-1 font-bold">Debug Mode</p>
                <p class="mt-0 text-sm font-light text-gray-600 md:mt-1">
                    @if ($debug === true)
                        The debug mode was expected to be <code class="code">false</code>, but actually was <code class="code">true</code>.
                    @else
                        false
                    @endif
                </p>
            </div>
        </div>

        <div class="card card-master flex items-start p-4 space-x-2 md:space-x-3">
            @if ($env !== 'production')
                <x-about.mark-fail/>
            @else
                <x-about.mark-check/>
            @endif

            <div>
                <p class="-mt-1 font-bold">Environment</p>
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

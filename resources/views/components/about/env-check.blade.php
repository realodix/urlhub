@php
    $debug = config('app.debug');
    $env = (string) app()->environment();
@endphp

<div class="px-2 my-6 md:mt-8 md:px-0">
    <dl class=" grid grid-cols-1 gap-2.5 sm:gap-3 md:gap-5 md:grid-cols-2">
        <div class="flex items-start p-4 space-x-2 overflow-hidden text-opacity-0 transition transform bg-white border border-border-200 rounded-xl md:space-x-3">
            @if ($debug === true)
                <div class="rounded-full p-0 md:p-2.5 md:bg-red-100 justify-center items-center flex">
                    <div class="absolute w-3.5 h-3.5 rounded-full bg-white"></div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 relative text-red-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            @else
                <div class="rounded-full p-0 md:p-2.5 md:bg-emerald-100 justify-center items-center flex">
                    <div class="absolute w-3.5 h-3.5 rounded-full bg-white"></div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 relative text-emerald-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            @endif

            <div>
                <dd class="-mt-1 font-bold text-gray-900">
                    Debug Mode
                </dd>
                <dt class="mt-0 text-sm font-light text-gray-600 md:mt-1">
                    @if ($debug === true)
                        The debug mode was expected to be <code class="code">false</code>, but actually was <code class="code">true</code>.
                    @else
                        false
                    @endif
                </dt>
            </div>
        </div>

        <div class="flex items-start p-4 space-x-2 overflow-hidden text-opacity-0 transition transform bg-white border border-border-200 rounded-xl md:space-x-3">
            @if ($env !== 'production')
                <div class="rounded-full p-0 md:p-2.5 md:bg-red-100 justify-center items-center flex">
                    <div class="absolute w-3.5 h-3.5 rounded-full bg-white"></div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 relative text-red-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                </div>
            @else
                <div class="rounded-full p-0 md:p-2.5 md:bg-emerald-100 justify-center items-center flex">
                    <div class="absolute w-3.5 h-3.5 rounded-full bg-white"></div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 relative text-emerald-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                </div>
            @endif

            <div>
                <dd class="-mt-1 font-bold text-gray-900">
                    Environment
                </dd>
                <dt class="mt-0 text-sm font-light text-gray-600 md:mt-1">
                    @if ($env !== 'production')
                        The environment was expected to be <code class="code">production</code>, but actually was <code class="code">{{ $env }}</code>.
                    @else
                        production
                    @endif
                </dt>
            </div>
        </div>
    </dl>
</div>

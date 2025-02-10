@if($errors->any())
    <div x-data="{ showNotification: true }">
        <div x-show="showNotification" role="alert" class="card relative mb-4 scroll-mt-7 py-3.5 pl-6.5 pr-4 dark:shadow-xs shadow-orange-600">
            <div class="absolute inset-y-2 left-2 w-0.5 rounded-full bg-orange-600"></div>
            <p class="mb-2 flex items-center gap-x-2 text-orange-600">
                @svg('icon-sign-warning', '!size-5')
                <span class="text-xs/4 font-medium">Warning</span>
            </p>
            <ul>
                @foreach ($errors->all() as $error)
                    <li class="text-slate-600 dark:text-dark-400">{{ $error }}</li>
                @endforeach
            </ul>
            <span x-on:click="showNotification = false" class="absolute top-0 right-0 mt-2 mr-2 cursor-pointer hover:bg-gray-100 px-1 rounded-sm">
                @svg('icon-close', 'size-4 text-gray-500 dark:text-red-400')
            </span>
        </div>
    </div>
@elseif (session('flash_success'))
    <div x-data="{ showNotification: true }">
        <div x-show="showNotification" role="alert" class="card relative mb-4 scroll-mt-7 py-3.5 pl-6.5 pr-4 dark:shadow-xs shadow-green-600">
            <div class="absolute inset-y-2 left-2 w-0.5 rounded-full bg-green-600"></div>
            <p class="mb-2 flex items-center gap-x-2 text-green-600">
                @svg('icon-sign-success', '!size-5')
                <span class="text-xs/4 font-medium">Success</span>
            </p>
            <p class="text-slate-600 dark:text-dark-400">{{ session('flash_success') }}</p>
            <span x-on:click="showNotification = false" class="absolute top-0 right-0 mt-2 mr-2 cursor-pointer hover:bg-gray-100 px-1 rounded-sm">
                @svg('icon-close', 'size-4 text-gray-500 dark:text-red-400')
            </span>
        </div>
    </div>

@elseif (session('flash_error'))
    <div role="alert"
        class="block mb-4 pl-3 pr-4 py-2 border-l-4
            text-base font-medium text-red-700 bg-red-50 border-red-600"
    >
        {{ session('flash_error') }}
    </div>
@endif

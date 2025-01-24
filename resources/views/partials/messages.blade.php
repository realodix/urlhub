@if($errors->any())
    <div role="alert" class="card relative mb-4 scroll-mt-7 py-3.5 pl-6.5 pr-4">
        <div class="absolute inset-y-2 left-2 w-0.5 rounded-full bg-orange-600"></div>
        <p class="mb-2 flex items-center gap-x-2 text-orange-600">
            <svg class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.23 20.77l3.54-3.54M20.77 20.77l-3.54-3.54M7 10.5v3M12 10.5v3M17 10.5v3M2 13v2c0 5 2 7 7 7h4M22 13V9c0-5-2-7-7-7H9C4 2 2 4 2 9"></path></svg>
            <span class="text-xs/4 font-medium">Warning</span>
        </p>
        <ul>
            @foreach ($errors->all() as $error)
                <li class="text-slate-600">{{ $error }}</li>
            @endforeach
        </ul>
    </div>

@elseif (session('flash_success'))
    <div role="alert" class="card relative mb-4 scroll-mt-7 py-3.5 pl-6.5 pr-4">
        <div class="absolute inset-y-2 left-2 w-0.5 rounded-full bg-green-600"></div>
        <p class="mb-2 flex items-center gap-x-2 text-green-600">
            <svg class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M22 13V9c0-5-2-7-7-7H9C4 2 2 4 2 9v6c0 5 2 7 7 7h4"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M22 13V9c0-5-2-7-7-7H9C4 2 2 4 2 9v6c0 5 2 7 7 7h4M7 10.74v3.2M17 10.74v3.2"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2 13v2c0 5 2 7 7 7h4M22 13V9c0-5-2-7-7-7H9C4 2 2 4 2 9M16 19.43L17.58 21 21 17M12 10.5v3"></path></svg>
            <span class="text-xs/4 font-medium">Success</span>
        </p>
        <p class="text-slate-600">{{ session('flash_success') }}</p>
    </div>

@elseif (session('flash_error'))
    <div role="alert"
        class="block mb-4 pl-3 pr-4 py-2 border-l-4
            text-base font-medium text-red-700 bg-red-50 border-red-600"
    >
        {{ session('flash_error') }}
    </div>
@endif

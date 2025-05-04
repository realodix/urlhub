<!DOCTYPE html>
<html lang="en">
<head>
    <title>{{ config('app.name') }} - Private Link</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @livewireStyles
    @vite(['resources/css/main.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased text-gray-800 overflow-hidden dark:bg-dark-950">
    <div class="min-h-screen flex flex-col justify-center items-center py-12 sm:px-6 lg:px-8 relative">
        <div class="relative card p-10 max-w-xl w-full z-10 shadow-xl">
            @if ($errors->any())
                <div class="bg-red-100 border-red-400 text-red-700 px-4 py-3 rounded relative mb-6 dark:bg-dark-950 dark:text-red-500 dark:border-red-500" role="alert">
                    <strong class="font-bold">Whoops!</strong>
                    <ul class="list-disc mt-2 ml-4">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mb-10 text-center">
                <div class="flex justify-center items-center">
                    <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                    <h2 class="ml-2 text-3xl font-extrabold text-gray-900 dark:text-dark-200">
                        Private Link
                    </h2>
                </div>
                <p class="mt-4 text-md text-gray-600 dark:text-dark-400">
                    This link is protected by a password. Please enter the correct password to continue.
                </p>
            </div>

            <div class="mb-8">
                <p class="text-center text-gray-700 font-medium dark:text-dark-400">
                    You are about to visit:
                </p>
                <code class="block break-all text-blue-700 dark:text-emerald-400 font-mono text-sm mt-3">
                    {{ $url->short_url }}
                </code>
            </div>

            <form method="post" action="{{ route('link.password.validate', $url) }}" class="space-y-6">
                @csrf
                <div>
                    <label for="password" class="block mb-2 text-sm font-medium text-gray-700 dark:text-dark-400">Password</label>
                    <div class="relative">
                        <input type="password" name="password" required placeholder="Enter your password" class="form-input">
                        <span class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </span>
                    </div>
                </div>

                <div class="text-center">
                    <button type="submit" class="w-full inline-flex justify-center py-3 px-6 border border-transparent rounded-md shadow-lg text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2">
                        <svg class="-ml-1 mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        Unlock
                    </button>
                </div>
            </form>
        </div>
    </div>

    @livewireScripts
</body>
</html>

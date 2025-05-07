<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Link Generation Error</title>
    @vite(['resources/css/main.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans flex items-center justify-center min-h-screen text-gray-800">
    <div class="bg-white p-8 rounded-lg shadow-lg text-center max-w-md w-full">
        <h1 class="text-3xl font-bold text-red-600 mb-4">
            Oops! Something Went Wrong
        </h1>

        <p class="text-gray-700 mb-6">
            {{ $message }}
        </p>

        <p>
            <a href="{{ url('/') }}" class="text-blue-600 hover:text-blue-800 hover:underline font-semibold">
                Back to Homepage
            </a>
        </p>
    </div>
</body>
</html>

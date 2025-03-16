<!DOCTYPE html>
<html lang="en">
<head>
    <title>{{ config('app.name') }}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @livewireStyles
    @vite(['resources/css/main.css', 'resources/js/app.js'])
</head>

<body>
    @include('partials/messages')

    <div class="container-alt max-w-100">
        <p class="mt-8 mb-4">
            You are about to go to the following page:
            <code class="block">{{ $url->short_url }}</code>
        </p>

        <form method="post" action="{{ route('link.password.validate', $url) }}">
        @csrf
            <input type="password" name="password" class="form-input" placeholder="Password">

            <br>

            <button type="submit" class="btn btn-primary">
                Submit
            </button>
        </form>
    </div>

    @livewireScripts
</body>
</html>

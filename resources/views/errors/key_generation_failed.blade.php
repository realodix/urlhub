@extends('layouts.general')

@section('title', 'Link Generation Error')
@section('css_class', 'error')
@section('content')
<div class="bg-gray-100 font-sans flex items-center justify-center min-h-screen text-gray-800">
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
</div>
@endsection

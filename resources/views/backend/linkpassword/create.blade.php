@extends('layouts.backend')

@section('title', 'Add Password')

@section('content')
    <div class="container-alt max-w-340">
        @include('partials.messages')

        <div class="content-container card max-w-160">
            <p class="text-2xl mb-8">
                Add Password for
                <a href="{{ route('link.edit', $url) }}" class="underline decoration-dotted">
                    {{ $url->keyword }}
                </a>
            </p>

            <form method="post" action="{{ route('link.password.store', $url) }}" class="space-y-6">
                @csrf
                <div class="grid grid-cols-6 gap-6">
                    <div class="col-span-6">
                        <label class="form-label" for="password">Password</label>
                        <input type="password" name="password" id="password" class="form-input" required>
                    </div>
                    <div class="col-span-6">
                        <label class="form-label" for="password_confirmation">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-input" required>
                    </div>
                </div>

                <div class="!mt-6 !mb-4 flex justify-end">
                    <button type="submit" class="btn btn-primary mt-2">
                        Set Password
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@extends('layouts.backend')

@section('title', __('Add Password'))

@section('content')
    <div class="container-alt max-w-340">
        @include('partials/messages')

        <div class="content-container card card-fluid">
            <h1>{{ __('Add Password for') }} <a href="{{ route('link.edit', $url) }}">{{ $url->keyword }}</a></h1>

            <form method="post" action="{{ route('link.password.store', $url) }}" class="space-y-6">
                @csrf
                <div class="grid grid-cols-6 gap-6">
                    <div class="col-span-6">
                        <label class="form-label" for="password">{{ __('Password') }}</label>
                        <input type="password" name="password" id="password" class="form-input" required>
                    </div>
                    <div class="col-span-6">
                        <label class="form-label" for="password_confirmation">{{ __('Confirm Password') }}</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-input" required>
                    </div>
                </div>

                <div class="!mt-6 !mb-4 flex justify-end">
                    <button type="submit" class="btn btn-primary mt-2">
                        {{ __('Set Password') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@extends('layouts.backend')

@section('title', __('Edit Password'))

@section('content')
    <div class="container-alt max-w-340">
        @include('partials.messages')

        <div class="content-container card card-fluid">
            <h1>Edit Password for <a href="{{ route('link.edit', $url) }}">{{ $url->keyword }}</a></h1>

            <form method="post" action="{{ route('link.password.update', $url) }}" class="space-y-6">
                @csrf
                <div class="grid grid-cols-6 gap-6">
                    <div class="col-span-6">
                        <label class="form-label" for="password">New Password</label>
                        <input type="password" name="password" required id="password" class="form-input">
                    </div>
                    <div class="col-span-6">
                        <label class="form-label" for="password_confirmation">Confirm New Password</label>
                        <input type="password" name="password_confirmation" required id="password_confirmation" class="form-input">
                    </div>
                </div>

                <div class="!mt-6 !mb-4 flex justify-end items-center">
                    <button type="submit" class="btn btn-primary mt-2">
                        Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

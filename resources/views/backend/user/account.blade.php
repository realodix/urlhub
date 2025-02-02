@extends('layouts.backend')

@section('title', __('Edit Account').' "'.$user->name.'"'.' ‹ '.str()->title(auth()->user()->name))

@section('content')

<div class="flex flex-wrap">
    <div class="w-full md:w-9/12">
        @include('partials/messages')
    </div>
</div>

<main class="container flex flex-wrap">
    <div class="md:w-3/12 flex justify-between">
        <div class="px-4 sm:px-0">
            <h3>{{ __('Account Information') }}</h3>

            <p class="mt-1 text-sm text-slate-600 dark:text-dark-400">
                {{ __("Update your account information.") }}
            </p>
        </div>
    </div>
    <div class="w-full md:w-8/12 lg:w-6/12 mt-5 md:mt-0 md:ml-4">
        <form method="post" action="{{ route('user.update', $user) }}">
        @csrf
            <div class="content-container card card-fluid">
                <div class="grid grid-cols-6 gap-6">
                    <div class="col-span-6 lg:col-span-4">
                        <label class="form-label">{{ __('Username') }}</label>
                        <input name="name" value="{{ $user->name }}" class="form-input mt-1" disabled>
                        <small class="block text-red-600 dark:text-red-500"><i>{{ __('Usernames cannot be changed.') }}</i></small>
                    </div>
                    <div class="col-span-6 lg:col-span-4">
                        <label class="form-label">{{ __('E-mail Address') }}</label>
                        <input type="email" name="email" value="{{ $user->email }}" class="form-input mt-1">
                    </div>
                </div>

                <div class="flex items-center justify-end mt-4 text-right">
                    <button type="submit" class="btn btn-primary btn-sm">
                        {{ __('Save') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</main>
@endsection

@extends('layouts.backend')

@section('title', __('Change User Password').' "'.$user->name.'"'.' ‹ '.str()->title(auth()->user()->name))

@section('content')
@include('partials/messages')

<main class="flex flex-wrap">
    <div class="md:w-3/12 flex justify-between">
        <div class="px-4 sm:px-0">
            <h3 class="text-lg font-medium text-slate-900">{{ __('Change Password') }}</h3>

            <p class="mt-1 text-sm text-slate-600">
                {{ __('Ensure your account is using a long, random password to stay secure.') }}
            </p>
        </div>
    </div>
    <div class="w-full md:w-8/12 lg:w-6/12 mt-5 md:mt-0 md:ml-4">
        <form method="post" action="{{ route('user.password.store', $user) }}">
        @csrf
            <div class="card-default">
                <div class="grid grid-cols-6 gap-6" x-data="{show: true}">
                    <div class="col-span-6 lg:col-span-4">
                        <label for="current_password" class="form-label">{{ __('Your Password') }}</label>
                        <input required type="password" name="current_password" placeholder="{{ __('Enter your password') }}"
                            class="form-input mt-1">
                    </div>

                    <div class="col-span-6 lg:col-span-4">
                        <label for="new_password" class="form-label">{{ __('New Password') }}</label>
                        <div class="relative">
                            <input required name="new_password" placeholder="{{ __('Enter a new password') }}"
                                :type="show ? 'password' : 'text'" class="form-input mt-1" >
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5">
                                <x-icon-eye-slash x-on:click="show=!show" ::class="{'!hidden': !show, 'block': show}" />
                                <x-icon-eye x-on:click="show=!show" ::class="{'block': !show, '!hidden': show}" />
                            </div>
                        </div>
                    </div>

                    <div class="col-span-6 lg:col-span-4">
                        <label for="new_password-confirm" class="form-label">{{ __('Confirmation') }}</label>
                        <div class="relative">
                            <input required name="new_password_confirmation" placeholder="Retype the new password"
                                class="form-input mt-1" :type="show ? 'password' : 'text'">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5">
                                <x-icon-eye-slash x-on:click="show=!show" ::class="{'!hidden': !show, 'block': show}" />
                                <x-icon-eye x-on:click="show=!show" ::class="{'block': !show, '!hidden': show}" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end mt-4 text-right">
                    <button type="submit" class="btn btn-primary btn-sm">
                        {{ __('Change Password') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</main>
@endsection

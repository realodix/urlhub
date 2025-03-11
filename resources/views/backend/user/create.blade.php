@extends('layouts.backend')

@section('title', __('Add New User'))

@section('content')

<div class="container-alt max-w-340">
    <div class="flex flex-wrap">
        <div class="md:w-3/12 flex justify-between">
            <div class="px-4 sm:px-0">
                <h3>{{ __('Add New User') }}</h3>

                <p class="mt-1 text-sm text-slate-600 dark:text-dark-400">
                    {{ __("Create a brand new user and add them to this site.") }}
                </p>
            </div>
        </div>
        <div class="w-full md:w-8/12 lg:w-6/12 mt-5 md:mt-0 md:ml-4">
            @include('partials/messages')

            <form method="post" action="{{ route('user.store') }}">
            @csrf
                <div class="content-container card card-fluid">
                    <div class="grid grid-cols-6 gap-6" x-data="{show: true}">
                        <div class="col-span-6 lg:col-span-4">
                            <label class="form-label">{{ __('Username') }}</label>
                            <input name="username" required class="form-input mt-1">
                        </div>
                        <div class="col-span-6 lg:col-span-4">
                            <label class="form-label">{{ __('E-mail Address') }}</label>
                            <input type="email" name="email" required class="form-input mt-1">
                        </div>
                        <div class="col-span-6 lg:col-span-4">
                            <label class="form-label">{{ __('Password') }}</label>
                            <div class="relative">
                                <input :type="show ? 'password' : 'text'" name="password" required placeholder="{{ __('Enter a new password') }}"
                                    class="form-input mt-1">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5">
                                    <x-icon-eye-slash x-on:click="show=!show" ::class="{'!hidden': !show, 'block': show}" />
                                    <x-icon-eye x-on:click="show=!show" ::class="{'block': !show, '!hidden': show}" />
                                </div>
                            </div>
                        </div>
                        <div class="col-span-6 lg:col-span-4">
                            <label class="form-label">{{ __('Role') }}</label>
                            <select name="role" class="form-input mt-1">
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-8 text-right">
                        <button type="submit" class="btn btn-primary btn-sm">
                            {{ __('Add New User') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

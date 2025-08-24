@extends('layouts.backend')

@section('title', 'Change User Password "'.$user->name.'" ‹ '.str()->title(auth()->user()->name))
@section('content')
<div class="container-alt max-w-340 flex flex-wrap">
    <div class="md:w-3/12 flex justify-between">
        <div class="px-4 sm:px-0">
            <h3>Change Password</h3>

            <p class="mt-1 text-sm text-slate-600 dark:text-dark-400">
                Ensure your account is using a long, random password to stay secure.
            </p>
        </div>
    </div>
    <div class="w-full md:w-8/12 lg:w-6/12 mt-5 md:mt-0 md:ml-4">
        @include('partials.messages')

        <form method="post" action="{{ route('user.password.update', $user) }}">
        @csrf
            <div class="content-container card card-master">
                <div class="grid grid-cols-6 gap-6" x-data="{show: true}">
                    <div class="col-span-6 lg:col-span-4">
                        <label class="form-label">Your Password</label>
                        <input type="password" name="current_password" required placeholder="Enter your password" class="form-input mt-1">
                    </div>

                    <div class="col-span-6 lg:col-span-4">
                        <label class="form-label">New Password</label>
                        <div class="relative">
                            <input x-bind:type="show ? 'password' : 'text'" name="new_password" required placeholder="Enter a new password" class="form-input mt-1" >
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5">
                                <x-icon-eye-slash x-on:click="show=!show" x-bind:class="{'hidden!': !show, 'block': show}" />
                                <x-icon-eye x-on:click="show=!show" x-bind:class="{'block text-red-700': !show, 'hidden!': show}" />
                            </div>
                        </div>
                    </div>

                    <div class="col-span-6 lg:col-span-4">
                        <label class="form-label">Confirmation</label>
                        <div class="relative">
                            <input x-bind:type="show ? 'password' : 'text'" name="new_password_confirmation" required placeholder="Retype the new password" class="form-input mt-1">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5">
                                <x-icon-eye-slash x-on:click="show=!show" x-bind:class="{'hidden!': !show, 'block': show}" />
                                <x-icon-eye x-on:click="show=!show" x-bind:class="{'block text-red-700': !show, 'hidden!': show}" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end mt-4 text-right">
                    <button type="submit" class="btn btn-primary btn-sm">
                        Change Password
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

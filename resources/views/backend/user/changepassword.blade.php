@extends('layouts.backend')

@section('title', Str::title(Auth::user()->name) .' ‹ '. __('Change Password'))

@section('content')

@include('partials/messages')

<main class="flex flex-wrap">
  <div class="md:w-3/12 flex justify-between">
    <div class="px-4 sm:px-0">
      <h3 class="text-lg font-medium text-slate-900">{{__('Change Password')}}</h3>

      <p class="mt-1 text-sm text-slate-600">
        {{__('Ensure your account is using a long, random password to stay secure.')}}
      </p>
    </div>
  </div>
  <div class="w-full md:w-8/12 lg:w-6/12 mt-5 md:mt-0 md:ml-4">
    <form method="post" action="{{route('user.change-password.post', $user->getRouteKey())}}">
    @csrf
      <div class="px-4 py-5 sm:p-6 bg-white shadow sm:rounded-tl-md sm:rounded-tr-md">
        <div class="grid grid-cols-6 gap-6" x-data="{show: true}">
          <div class="col-span-6 lg:col-span-4">
            <label for="current-password" class="block font-medium text-sm text-slate-700">{{__('Your Password')}}</label>
            <input id="current-password" type="password" name="current-password" placeholder="Enter your password" class="form-input mt-1" required>
          </div>

          <div class="col-span-6 lg:col-span-4">
            <label for="new-password" class="block font-medium text-sm text-slate-700">{{__('New Password')}}</label>
            <div class="relative">
              <input :type="show ? 'password' : 'text'" id="new-password" name="new-password" aria-label="Enter a new password" placeholder="Enter a new password" class="form-input mt-1" required>
              <div class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5">
                <x-fas-eye @click="show=!show" ::class="{'!hidden': !show, 'block':show}" />
                <x-fas-eye-slash @click="show=!show" ::class="{'block': !show, '!hidden':show}" />
              </div>
            </div>

          </div>

          <div class="col-span-6 lg:col-span-4">
            <label for="new-password-confirm" class="block font-medium text-sm text-slate-700">{{__('Confirmation')}}</label>
            <div class="relative">
              <input :type="show ? 'password' : 'text'" id="new-password-confirm" name="new-password_confirmation" aria-label="Retype the new password" placeholder="Retype the new password" class="form-input mt-1" required>
              <div class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5">
                <x-fas-eye @click="show=!show" ::class="{'!hidden': !show, 'block':show}" />
                <x-fas-eye-slash @click="show=!show" ::class="{'block': !show, '!hidden':show}" />
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="flex items-center justify-end px-4 py-3 sm:px-6
            text-right bg-bg-primary sm:bg-slate-50
            border-t sm:rounded-bl-md sm:rounded-br-md sm:shadow"
      >
        <button type="submit" class="btn">
          {{__('Change Password')}}
        </button>
      </div>
    </form>
  </div>
</main>
@endsection

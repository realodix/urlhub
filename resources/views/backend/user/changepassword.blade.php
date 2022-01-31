@extends('layouts.backend')

@section('title', Str::title(Auth::user()->name) .' ‹ '. __('Change Password'))

@section('content')

@include('messages')

<main class="md:grid md:grid-cols-3 md:gap-6">
  <div class="md:col-span-1 flex justify-between">
    <div class="px-4 sm:px-0">
      <h3 class="text-lg font-medium text-gray-900">@lang('Change Password')</h3>

      <p class="mt-1 text-sm text-gray-600">
        @lang('Ensure your account is using a long, random password to stay secure.')
      </p>
    </div>
  </div>
  <div class="mt-5 sm:mt-0 md:col-span-2">
    <form method="post" action="{{route('user.change-password.post', $user->getRouteKey())}}">
    @csrf
      <div class="bg-white px-4 py-5  sm:p-6 shadow sm:rounded-tl-md sm:rounded-tr-md">
        <div class="grid grid-cols-6 gap-6">
          <div class="col-span-6 sm:col-span-4">
            <label for="current-password" class="block font-medium text-sm text-gray-700">@lang('Your Password')</label>
            <input id="current-password" type="password" name="current-password" placeholder="Enter your password" class="form-input mt-1" required>
          </div>

          <div class="col-span-6 sm:col-span-4">
            <label for="new-password" class="block font-medium text-sm text-gray-700">@lang('New Password')</label>
            <input type="password" id="new-password" name="new-password" aria-label="Enter a new password" placeholder="Enter a new password" class="form-input mt-1" required>
          </div>

          <div class="col-span-6 sm:col-span-4">
            <label for="new-password-confirm" class="block font-medium text-sm text-gray-700">@lang('Confirmation')</label>
            <input type="password" id="new-password-confirm" name="new-password_confirmation" aria-label="Retype the new password" placeholder="Retype the new password" class="form-input mt-1" required>
          </div>
        </div>
      </div>
      <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md">
        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
          @lang('Change Password')
        </button>
      </div>
    </form>
  </div>
</main>
@endsection

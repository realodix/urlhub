@extends('layouts.backend')

@section('title', Str::title(Auth::user()->name) .' ‹ '. __('Change Password'))

@section('content')

@include('partials/messages')

<main class="flex flex-wrap">
  <div class="md:w-3/12 flex justify-between">
    <div class="px-4 sm:px-0">
      <h3 class="text-lg font-medium text-slate-900">@lang('Change Password')</h3>

      <p class="mt-1 text-sm text-slate-600">
        @lang('Ensure your account is using a long, random password to stay secure.')
      </p>
    </div>
  </div>
  <div class="w-full md:w-6/12 mt-5 md:mt-0 md:ml-4 bg-white">
    <form method="post" action="{{route('user.change-password.post', $user->getRouteKey())}}">
    @csrf
      <div class="px-4 py-5  sm:p-6 shadow sm:rounded-tl-md sm:rounded-tr-md">
        <div class="grid grid-cols-6 gap-6">
          <div class="col-span-6 sm:col-span-4">
            <label for="current-password" class="block font-medium text-sm text-slate-700">@lang('Your Password')</label>
            <input id="current-password" type="password" name="current-password" placeholder="Enter your password" class="form-input mt-1" required>
          </div>

          <div class="col-span-6 sm:col-span-4">
            <label for="new-password" class="block font-medium text-sm text-slate-700">@lang('New Password')</label>
            <input type="password" id="new-password" name="new-password" aria-label="Enter a new password" placeholder="Enter a new password" class="form-input mt-1" required>
          </div>

          <div class="col-span-6 sm:col-span-4">
            <label for="new-password-confirm" class="block font-medium text-sm text-slate-700">@lang('Confirmation')</label>
            <input type="password" id="new-password-confirm" name="new-password_confirmation" aria-label="Retype the new password" placeholder="Retype the new password" class="form-input mt-1" required>
          </div>
        </div>
      </div>
      <div class="flex items-center justify-end px-4 py-3 bg-slate-50 text-right sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md border-t">
        <button type="submit" class="button">
          @lang('Change Password')
        </button>
      </div>
    </form>
  </div>
</main>
@endsection

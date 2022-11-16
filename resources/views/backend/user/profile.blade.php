@extends('layouts.backend')

@section('title', Str::title(Auth::user()->name) .' ‹ '. __('Edit Profile'))

@section('content')

@include('partials/messages')

<main class="flex flex-wrap">
    <div class="md:w-3/12 flex justify-between">
        <div class="px-4 sm:px-0">
            <h3 class="text-lg font-medium text-slate-900">{{__('Profile Information')}}</h3>

            <p class="mt-1 text-sm text-slate-600">
                {{__("Update your account's profile information and email address.")}}
            </p>
        </div>
    </div>
    <div class="w-full md:w-8/12 lg:w-6/12 mt-5 md:mt-0 md:ml-4">
        <form method="post" action="{{route('user.update', $user->getRouteKey())}}">
        @csrf
            <div class="px-4 py-5 sm:p-6 bg-white shadow sm:rounded-tl-md sm:rounded-tr-md">
                <div class="grid grid-cols-6 gap-6">
                    <div class="col-span-6 lg:col-span-4">
                        <label for="name" class="block font-medium text-sm text-slate-700">{{__('Username')}}</label>
                        <input value="{{$user->name}}" id="name" type="text" name="name"
                            class="form-input bg-slate-100 text-slate-700 mt-1" disabled>
                        <small class="block text-red-400"><i>{{__('Usernames cannot be changed.')}}</i></small>
                    </div>
                    <div class="col-span-6 lg:col-span-4">
                        <label for="email" class="block font-medium text-sm text-slate-700">{{__('E-mail
                            Address')}}</label>
                        <input value="{{$user->email}}" id="email" type="email" name="email" class="form-input mt-1">
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-end px-4 py-3 sm:px-6
                text-right bg-bg-primary sm:bg-slate-50
                border-t sm:rounded-bl-md sm:rounded-br-md sm:shadow"
            >
                <button type="submit" class="btn btn-primary btn-sm">
                    {{__('Save')}}
                </button>
            </div>
        </form>
    </div>
</main>
@endsection

@extends('layouts.backend')

@section('title', Str::title(auth()->user()->name) .' ‹ '. __('Edit Profile'))

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
                <div class="common-card-style sm:rounded-b-none px-4 py-5 sm:p-6">
                    <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6 lg:col-span-4">
                            <label class="block font-medium text-sm text-slate-700">{{__('Username')}}</label>
                            <input type="text" name="name" value="{{$user->name}}" class="form-input bg-slate-100 text-slate-700 mt-1" disabled>
                            <small class="block text-red-400"><i>{{__('Usernames cannot be changed.')}}</i></small>
                        </div>
                        <div class="col-span-6 lg:col-span-4">
                            <label class="block font-medium text-sm text-slate-700">{{__('E-mail Address')}}</label>
                            <input type="email" name="email" value="{{$user->email}}" class="form-input mt-1">
                        </div>
                    </div>
                </div>
                <div class="common-card-style bg-bg-primary sm:bg-slate-50 sm:rounded-t-none
                    flex items-center justify-end px-4 py-3 sm:px-6
                    text-right border-t"
                >
                    <button type="submit" class="btn btn-primary btn-sm">
                        {{__('Save')}}
                    </button>
                </div>
            </form>
        </div>
    </main>
@endsection

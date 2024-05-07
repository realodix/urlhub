@extends('layouts.backend')

@section('title', Str::title(auth()->user()->name) .' ‹ '. __('Edit Account'))

@section('content')
    @include('partials/messages')

    <main class="flex flex-wrap">
        <div class="md:w-3/12 flex justify-between">
            <div class="px-4 sm:px-0">
                <h3 class="text-lg font-medium text-slate-900">{{__('Account Information')}}</h3>

                <p class="mt-1 text-sm text-slate-600">
                    {{__("Update your account information.")}}
                </p>
            </div>
        </div>
        <div class="w-full md:w-8/12 lg:w-6/12 mt-5 md:mt-0 md:ml-4">
            <form method="post" action="{{route('user.update', $user)}}">
            @csrf
                <div class="common-card-style">
                    <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6 lg:col-span-4">
                            <label class="form-label">{{__('Username')}}</label>
                            <input name="name" value="{{$user->name}}" class="form-input bg-slate-100 text-slate-700 mt-1" disabled>
                            <small class="block text-red-400"><i>{{__('Usernames cannot be changed.')}}</i></small>
                        </div>
                        <div class="col-span-6 lg:col-span-4">
                            <label class="form-label">{{__('E-mail Address')}}</label>
                            <input type="email" name="email" value="{{$user->email}}" class="form-input mt-1">
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-4 text-right">
                        <button type="submit" class="btn btn-primary btn-sm">
                            {{__('Save')}}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </main>
@endsection

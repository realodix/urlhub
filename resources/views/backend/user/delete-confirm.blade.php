@extends('layouts.backend')

@section('title', $user->name)

@section('content')
<div class="container-alt">
    <div class="card max-w-4xl ml-0 lg:ml-12 p-8">
        <div class="font-bold text-xl mb-4">Confirm User Deletion</div>
        <div class="card-body">
            <p>Are you sure you want to delete user: <strong class="text-primary-700 dark:text-primary-500">{{ $user->name }} ({{ $user->email }})</strong>? All of the user's data will be permanently deleted. This action cannot be undone.</p>

            <br>
            <br>

            <div class="flex justify-end">
                <form action="{{ route('user.delete', $user) }}" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-delete-danger">
                        {{ __('Confirm Deletion') }}
                    </button>
                    <a href="{{ url()->previous() }}" class="btn btn-sm btn-secondary ml-4">{{ __('Cancel') }}</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

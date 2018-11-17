<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class UserPolicy
{
    use HandlesAuthorization;

    public function view(User $authenticatedUser, User $user)
    {
        return Auth::user()->hasRole('admin') || $authenticatedUser->id === $user->id;
    }

    public function create()
    {
        //
    }

    public function update(User $authenticatedUser, User $user)
    {
        return Auth::user()->hasRole('admin') || $authenticatedUser->id === $user->id;
    }

    public function updatePass(User $authenticatedUser, User $user)
    {
        return Auth::user()->hasRole('admin') || $authenticatedUser->id === $user->id;
    }

    public function delete()
    {
        //
    }

    public function restore()
    {
        //
    }

    public function forceDelete()
    {
        //
    }
}

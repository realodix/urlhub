<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * @param \App\User $authenticatedUser
     * @param \App\User $user
     * @return bool
     */
    public function view(User $authenticatedUser, User $user)
    {
        return Auth::user()->hasRole('admin') || $authenticatedUser->id === $user->id;
    }

    /**
     * @param \App\User $authenticatedUser
     * @param \App\User $user
     * @return bool
     */
    public function update(User $authenticatedUser, User $user)
    {
        return Auth::user()->hasRole('admin') || $authenticatedUser->id === $user->id;
    }

    /**
     * @param \App\User $authenticatedUser
     * @param \App\User $user
     * @return bool
     */
    public function updatePass(User $authenticatedUser, User $user)
    {
        return Auth::user()->hasRole('admin') || $authenticatedUser->id === $user->id;
    }
}

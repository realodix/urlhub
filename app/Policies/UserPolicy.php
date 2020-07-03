<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * @param \App\Models\User $authenticatedUser
     * @param \App\Models\User $user
     * @return bool
     */
    public function view(User $authenticatedUser, User $user)
    {
        return Auth::user()->hasRole('admin') || $authenticatedUser->id === $user->id;
    }

    /**
     * @param \App\Models\User $authenticatedUser
     * @param \App\Models\User $user
     * @return bool
     */
    public function update(User $authenticatedUser, User $user)
    {
        return Auth::user()->hasRole('admin') || $authenticatedUser->id === $user->id;
    }

    /**
     * @param \App\Models\User $authenticatedUser
     * @param \App\Models\User $user
     * @return bool
     */
    public function updatePass(User $authenticatedUser, User $user)
    {
        return Auth::user()->hasRole('admin') || $authenticatedUser->id === $user->id;
    }
}

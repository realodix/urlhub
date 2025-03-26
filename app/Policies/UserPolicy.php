<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the given user is the owner URL or is an administrator.
     */
    public function authorOrAdmin(User $authUser, User $user): bool
    {
        return $authUser->hasRole('admin') || $authUser->id === $user->id;
    }

    /**
     * Determine whether the user can permanently delete the user.
     */
    public function forceDelete(User $authUser, User $user): bool
    {
        return $authUser->hasRole('admin') && $authUser->id !== $user->id;
    }
}

<?php

namespace App\Policies;

use App\Models\Url;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UrlPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can permanently delete the url.
     */
    public function forceDelete(User $user, Url $url): bool
    {
        return $user->hasRole('admin') || $user->id === $url->user_id;
    }

    /**
     * Determine whether the user can update the url.
     */
    public function updateUrl(User $user, Url $url): bool
    {
        return $user->hasRole('admin') || $user->id === $url->user_id;
    }
}

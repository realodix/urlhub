<?php

namespace App\Policies;

use App\Models\Url;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UrlPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the given user is the owner URL or is an administrator.
     */
    public function authorOrAdmin(User $user, Url $url): bool
    {
        return $user->hasRole('admin') || $user->id === $url->user_id;
    }
}

<?php

namespace App\Policies;

use App\Url;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class UrlPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can permanently delete the url.
     *
     * @param \App\User $user
     * @param \App\Url  $url
     * @return bool
     */
    public function forceDelete(User $user, Url $url)
    {
        return Auth::user()->hasRole('admin') || $user->id === $url->user_id;
    }
}

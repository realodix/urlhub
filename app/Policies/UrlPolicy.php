<?php

namespace App\Policies;

use App\Models\Url;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class UrlPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can permanently delete the url.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Url  $url
     * @return bool
     */
    public function forceDelete(User $user, Url $url)
    {
        return Auth::user()->hasRole('admin') || $user->id === $url->user_id;
    }

    /**
     * Determine whether the user can update the url.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Url  $url
     * @return bool
     */
    public function updateUrl(User $user, Url $url)
    {
        return Auth::user()->hasRole('admin') || $user->id === $url->user_id;
    }
}

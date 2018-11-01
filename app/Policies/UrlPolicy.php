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
     * Determine whether the user can view the url.
     *
     * @param  \App\User  $user
     * @param  \App\Url  $url
     * @return mixed
     */
    public function view(User $user, Url $url)
    {
        //
    }

    /**
     * Determine whether the user can create urls.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the url.
     *
     * @param  \App\User  $user
     * @param  \App\Url  $url
     * @return mixed
     */
    public function update(User $user, Url $url)
    {
        //
    }

    /**
     * Determine whether the user can delete the url.
     *
     * @param  \App\User $user
     * @param  \App\Url  $url
     * @return mixed
     */
    public function delete(User $user, Url $url)
    {
        ///
    }

    /**
     * Determine whether the user can restore the url.
     *
     * @param  \App\User  $user
     * @param  \App\Url  $url
     * @return mixed
     */
    public function restore(User $user, Url $url)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the url.
     *
     * @param  \App\User  $user
     * @param  \App\Url  $url
     * @return mixed
     */
    public function forceDelete(User $user, Url $url)
    {
        return Auth::user()->hasRole('admin') || $user->id === $url->user_id;
    }
}

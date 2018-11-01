<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class UserPolicy
{
    use HandlesAuthorization;

    public function view()
    {
        return Auth::user()->hasRole('admin') || Auth::user()->name == request()->route()->parameter('user');
    }

    public function create()
    {
        //
    }

    public function update()
    {
        return Auth::user()->hasRole('admin') || Auth::user()->name == request()->route()->parameter('user');
    }

    public function updatePass()
    {
        return Auth::user()->hasRole('admin') || Auth::user()->name == request()->route()->parameter('user');
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

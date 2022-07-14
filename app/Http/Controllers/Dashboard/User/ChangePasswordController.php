<?php

namespace App\Http\Controllers\Dashboard\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserPassword;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    /**
     * Show the form for editing password.
     *
     * @param  User  $user  \App\Models\User
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function view(User $user)
    {
        $this->authorize('view', $user);

        return view('backend.user.changepassword', compact('user'));
    }

    /**
     * Change the password.
     *
     * @param  UpdateUserPassword  $request  \App\Http\Requests\UpdateUserPassword
     * @param  User  $user  \App\Models\User
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(UpdateUserPassword $request, User $user)
    {
        $this->authorize('updatePass', $user);

        $user->password = Hash::make($request['new-password']);
        $user->save();

        return redirect()->back()
                         ->withFlashSuccess(__('Password changed successfully !'));
    }
}

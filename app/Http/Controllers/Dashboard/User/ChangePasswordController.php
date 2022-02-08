<?php

namespace App\Http\Controllers\Dashboard\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserPassword;
use App\Models\User;
use App\Services\UserService;

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
        $userSrvc = new UserService;

        $this->authorize('updatePass', $user);

        $data = $request->only('new-password');

        $userSrvc->updateUserPassword($data, $user);

        return redirect()->back()
                         ->withFlashSuccess(__('Password changed successfully !'));
    }
}

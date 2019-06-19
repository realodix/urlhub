<?php

namespace App\Http\Controllers\Backend\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserPassword;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    /**
     * Show the form for editing password.
     *
     * @param \App\User $user
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
     * @param \App\Http\Requests\UpdateUserPassword $request
     * @param \App\User                             $user
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(UpdateUserPassword $request, User $user)
    {
        $this->authorize('updatePass', $user);

        $user->password = Hash::make($request->input('new-password'));
        $user->save();

        return redirect()->back()
                         ->withFlashSuccess(__('Password changed successfully !'));
    }
}

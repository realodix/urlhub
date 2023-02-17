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
     * @param User $user \App\Models\User
     * @return \Illuminate\Contracts\View\View
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function view(User $user)
    {
        $this->authorize('view', $user);

        return view('backend.user.changepassword', ['user' => $user]);
    }

    /**
     * Change the password.
     *
     * @param UpdateUserPassword $request \App\Http\Requests\UpdateUserPassword
     * @param User               $hash_id \App\Models\User
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(UpdateUserPassword $request, User $hash_id)
    {
        $this->authorize('updatePass', $hash_id);

        $hash_id->password = Hash::make($request['new-password']);
        $hash_id->save();

        return redirect()->back()
            ->withFlashSuccess(__('Password changed successfully !'));
    }
}

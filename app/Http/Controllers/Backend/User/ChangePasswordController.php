<?php

namespace App\Http\Controllers\Backend\User;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    /**
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
     * @param \Illuminate\Http\Request $request
     * @param \App\User                $user
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('updatePass', $user);

        if (! (Hash::check($request->input('current-password'), Auth::user()->password))) {
            // The passwords matches
            return redirect()->back()
                             ->withFlashError(__('The password you entered does not match your password. Please try again.'));
        }

        if (strcmp($request->input('current-password'), $request->input('new-password')) == 0) {
            // Current password and new password are same
            return redirect()->back()
                             ->withFlashError(__('New Password cannot be same as your current password. Please choose a different password.'));
        }

        $validatedData = $request->validate([
            'new-password' => 'required|string|min:6|confirmed',
        ]);

        // Change password
        $user->password = Hash::make($request->input('new-password'));
        $user->save();

        return redirect()->back()
                         ->withFlashSuccess(__('Password changed successfully !'));
    }
}

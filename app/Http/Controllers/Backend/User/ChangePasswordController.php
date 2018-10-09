<?php

namespace App\Http\Controllers\Backend\User;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    public function view($user)
    {
        if ((Auth::user()->name != $user) && Auth::user()->hasRole('admin') == false) {
            return abort(403);
        }

        $user = User::where('name', $user)->firstOrFail();

        return view('backend.user.changepassword', [
            'name'  => $user->name,
        ]);
    }

    public function update(Request $request, $user)
    {
        if (!(Hash::check($request->input('current-password'), Auth::user()->password))) {
            // The passwords matches
            return redirect()->back()->with('error', __('Your current password does not matches with the password you provided. Please try again.'));
        }

        if (strcmp($request->input('current-password'), $request->input('new-password')) == 0) {
            //Current password and new password are same
            return redirect()->back()->with('error', __('New Password cannot be same as your current password. Please choose a different password.'));
        }

        $validatedData = $request->validate([
            'new-password'     => 'required|string|min:6|confirmed',
        ]);

        //Change Password
        $user = User::where('name', $user)->first();
        $user->password = Hash::make($request->input('new-password'));
        $user->save();

        return redirect()->back()->with('success', __('Password changed successfully !'));
    }
}

<?php

namespace App\Http\Controllers\Dashboard\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserPassword;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
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
        Gate::authorize('view', $user);

        return view('backend.user.changepassword', ['user' => $user]);
    }

    /**
     * Change the password.
     *
     * @param UpdateUserPassword $request \App\Http\Requests\UpdateUserPassword
     * @param User $user \App\Models\User
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(UpdateUserPassword $request, User $user)
    {
        Gate::authorize('updatePass', $user);

        $newPassword = $request->new_password;

        // Check if admin user is changing another user's password.
        // Admin authority check has been done by the gate.
        if (!Auth::user()->is($user)) {
            $user->password = Hash::make($newPassword);
            $user->save();
        } else {
            $request->user()->password = Hash::make($newPassword);
            $request->user()->save();

            // Clear sessions on other devices
            Auth::logoutOtherDevices($newPassword);
        }

        return redirect()->back()
            ->with('flash_success', __('Password changed successfully !'));
    }
}

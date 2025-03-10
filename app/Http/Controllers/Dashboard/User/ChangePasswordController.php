<?php

namespace App\Http\Controllers\Dashboard\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

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
     * @param Request $request \Illuminate\Http\Request
     * @param User $user \App\Models\User
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, User $user)
    {
        Gate::authorize('updatePass', $user);

        $request->validate([
            'current_password' => ['current_password'],
            'new_password' => [
                ...\App\Rules\UserRules::passwordWithConfirm(),
                'unique:users,password', 'different:current_password',
            ],
        ]);

        $newPassword = $request->new_password;

        // Check if admin user is changing another user's password.
        // Admin authority check has been done by the gate.
        if (!auth()->user()->is($user)) {
            $user->password = $newPassword;
            $user->save();
        } else {
            $request->user()->password = $newPassword;
            $request->user()->save();

            // Clear sessions on other devices
            auth()->logoutOtherDevices($newPassword);
        }

        return redirect()->back()
            ->with('flash_success', __('Password changed successfully !'));
    }
}

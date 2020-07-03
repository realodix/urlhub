<?php

namespace App\Http\Controllers\Dashboard\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserPassword;
use App\Models\User;
use App\Services\UserService;

class ChangePasswordController extends Controller
{
    /**
     * @var userService
     */
    protected $userService;

    /**
     * ChangePasswordController constructor.
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Show the form for editing password.
     *
     * @param \App\Models\User $user
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
     * @param \App\Models\User                      $user
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(UpdateUserPassword $request, User $user)
    {
        $this->authorize('updatePass', $user);

        $data = $request->only('new-password');

        $this->userService->updateUserPassword($data, $user);

        return redirect()->back()
                         ->withFlashSuccess(__('Password changed successfully !'));
    }
}

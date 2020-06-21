<?php

namespace App\Http\Controllers\Dashboard\User;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\UserService;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * UserController constructor.
     */
    public function __construct()
    {
        $this->middleware('role:admin')->only('view');
    }

    /**
     * Display a listing of the users.
     */
    public function view()
    {
        return view('backend.user.index');
    }

    /**
     * @codeCoverageIgnore
     */
    public function dataTable(UserService $userService)
    {
        return $userService->dataTable();
    }

    /**
     * Show the form for editing the specified user.
     *
     * @param \App\User $user
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(User $user)
    {
        $this->authorize('view', $user);

        return view('backend.user.profile', compact('user'));
    }

    /**
     * Update the specified user in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\User                $user
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $user->email = $request->input('email');

        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        ]);

        $user->save();

        return redirect()->back()
                         ->withFlashSuccess(__('Profile updated.'));
    }
}

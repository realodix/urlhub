<?php

namespace App\Http\Controllers\Dashboard\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserEmail;
use App\Models\User;

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
     *
     * @return \Illuminate\View\View
     */
    public function view()
    {
        return view('backend.user.index');
    }

    /**
     * Show the form for editing the specified user.
     *
     * @param User $user \App\Models\User
     * @return \Illuminate\View\View
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
     * @param UpdateUserEmail $request \App\Http\Requests\UpdateUserEmail
     * @param User            $user    \App\Models\User
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(UpdateUserEmail $request, User $user)
    {
        $this->authorize('update', $user);

        $user->email = $request->email;
        $user->save();

        return redirect()->back()
            ->withFlashSuccess(__('Profile updated.'));
    }
}

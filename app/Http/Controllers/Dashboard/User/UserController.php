<?php

namespace App\Http\Controllers\Dashboard\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserEmail;
use App\Models\User;
use App\Services\UserService;

class UserController extends Controller
{
    /**
     * UserController constructor.
     *
     * @param  UserService  $userSrvc  \App\Services\UserService
     */
    public function __construct(protected UserService $userSrvc)
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
     * Show the form for editing the specified user.
     *
     * @param  User  $user  \App\Models\User
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
     * @param  UpdateUserEmail $request \App\Http\Requests\UpdateUserEmail
     * @param  User            $user    \App\Models\User
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(UpdateUserEmail $request, User $user)
    {
        $this->authorize('update', $user);

        $user->email = $request->only('email');
        $user->save();

        return redirect()->back()
                         ->withFlashSuccess(__('Profile updated.'));
    }
}

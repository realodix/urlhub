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
     * @return \Illuminate\Contracts\View\View
     */
    public function view()
    {
        return view('backend.user.index');
    }

    /**
     * Show the form for editing the specified user.
     *
     * @param User $user \App\Models\User
     * @return \Illuminate\Contracts\View\View
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(User $user)
    {
        $this->authorize('view', $user);

        return view('backend.user.profile', ['user' => $user]);
    }

    /**
     * Update the specified user in storage.
     *
     * @param UpdateUserEmail $request \App\Http\Requests\UpdateUserEmail
     * @param User            $hash_id \App\Models\User
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(UpdateUserEmail $request, User $hash_id)
    {
        $this->authorize('update', $hash_id);

        $hash_id->email = $request->email;
        $hash_id->save();

        return redirect()->back()
            ->withFlashSuccess(__('Profile updated.'));
    }
}

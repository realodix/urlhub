<?php

namespace App\Http\Controllers\Dashboard\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserEmail;
use App\Models\User;
use Illuminate\Routing\Controllers\{HasMiddleware, Middleware};
use Illuminate\Support\Facades\Gate;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [new Middleware('role:admin', only: ['view'])];
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
        Gate::authorize('view', $user);

        return view('backend.user.account', ['user' => $user]);
    }

    /**
     * Update the specified user in storage.
     *
     * @param UpdateUserEmail $request \App\Http\Requests\UpdateUserEmail
     * @param User $user \App\Models\User
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(UpdateUserEmail $request, User $user)
    {
        Gate::authorize('update', $user);

        $user->email = $request->email;
        $user->save();

        return redirect()->back()
            ->withFlashSuccess(__('Account updated.'));
    }
}

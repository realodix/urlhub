<?php

namespace App\Http\Controllers\Dashboard\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\{HasMiddleware, Middleware};
use Illuminate\Support\Facades\Gate;
use Jackiedo\Timezonelist\Facades\Timezonelist;

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

        $tzList = Timezonelist::toSelectBox('user_timezone', $user->timezone);

        return view('backend.user.account', [
            'user' => $user,
            'timezoneList' => $tzList,
        ]);
    }

    /**
     * Update the specified user in storage.
     *
     * @param Request $request \Illuminate\Http\Request
     * @param User $user \App\Models\User
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, User $user)
    {
        Gate::authorize('update', $user);

        $forwardQuery = $request->forward_query ? true : false;

        if ($user->email == $request->email) {
            $user->forward_query = $forwardQuery;
        } else {
            $request->validate([
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            ]);
            $user->email = $request->email;
            $user->forward_query = $forwardQuery;
        }

        $user->timezone = $request->user_timezone;

        $user->save();

        return redirect()->back()
            ->with('flash_success', __('Account updated.'));
    }
}

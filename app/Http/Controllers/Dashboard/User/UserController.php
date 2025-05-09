<?php

namespace App\Http\Controllers\Dashboard\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Rules\UserRules;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\{HasMiddleware, Middleware};
use Illuminate\Support\Facades\Gate;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [new Middleware('role:admin', only: ['view', 'create'])];
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
     * Show the form for creating a new user.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('backend.user.create');
    }

    /**
     * Store a newly created user in storage.
     *
     * @param Request $request \Illuminate\Http\Request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => ['required', ...UserRules::name()],
            'email' => ['required', ...UserRules::email()],
            'password' => ['required', ...UserRules::password()],
        ]);

        $user = User::create([
            'name' => strtolower($request->username),
            'email' => strtolower($request->email),
            'password' => $request->password,
        ]);

        if ($request->role == 'admin') {
            $user->assignRole('admin');
        }

        return to_route('user.edit', $user);
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
        Gate::authorize('authorOrAdmin', $user);

        $tzList = app(\Realodix\Timezone\CompactTimezone::class)
            ->toSelectBox('user_timezone', $user->timezone, [
                'class' => 'form-input',
            ]);

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
        Gate::authorize('authorOrAdmin', $user);

        $data = [
            'forward_query' => $request->forward_query ? true : false,
            'timezone' => $request->user_timezone,
        ];

        if ($request->email !== null) {
            $request->validate(['email' => UserRules::email()]);
            $data['email'] = $request->email;
        }

        $user->update($data);

        return redirect()->back()
            ->with('flash_success', 'Account updated.');
    }

    /**
     * Delete a user.
     *
     * @param User $user \App\Models\User
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function delete(User $user)
    {
        Gate::authorize('forceDelete', $user);

        $user->delete();

        return redirect()->route('user.index')
            ->with('flash_success', 'User deleted.');
    }

    /**
     * Show the confirmation page for deleting a user.
     *
     * @param User $user \App\Models\User
     * @return \Illuminate\Contracts\View\View
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function confirmDelete(User $user)
    {
        Gate::authorize('forceDelete', $user);

        return view('backend.user.delete-confirm', ['user' => $user]);
    }
}

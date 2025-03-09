<?php

namespace App\Http\Controllers\Dashboard\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Rules\PasswordRules;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\{HasMiddleware, Middleware};
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Realodix\Timezone\Timezone;

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
            'username' => ['required', 'string', 'max:50'],
            'email'    => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)],
            'password' => PasswordRules::rule(),
        ]);

        $user = User::create([
            'name' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
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
        Gate::authorize('view', $user);

        $tzList = app(Timezone::class)
            ->toSelectBox('user_timezone', $user->timezone, ['class' => 'form-input']);

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

        $data = [
            'forward_query' => $request->forward_query ? true : false,
            'timezone' => $request->user_timezone,
        ];

        if ($request->email != $user->email) {
            $request->validate([
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            ]);
            $data['email'] = $request->email;
        }

        $user->update($data);

        return redirect()->back()
            ->with('flash_success', __('Account updated.'));
    }
}

<?php

namespace App\Http\Controllers\Dashboard\User;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\UserService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * @var userService
     */
    protected $userService;

    /**
     * UserController constructor.
     */
    public function __construct(UserService $userService)
    {
        $this->middleware('role:admin')->only('view');
        $this->userService = $userService;
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
    public function dataTable()
    {
        return $this->userService->dataTable();
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
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $data = $request->only('email');

        $v = Validator::make($data, [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        ]);

        if ($v->fails()) {
            return redirect()->back()
                             ->withFlashError($v->errors()->first());
        }

        $this->userService->update($data, $user);

        return redirect()->back()
                         ->withFlashSuccess(__('Profile updated.'));
    }
}

<?php

namespace App\Http\Controllers\Dashboard\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserEmail;
use App\Services\Dashboard\UserService;
use App\User;

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
     * @param \App\Http\Requests\UpdateUserEmail $request
     * @param \App\User                          $user
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(UpdateUserEmail $request, User $user)
    {
        $this->authorize('update', $user);

        $data = $request->only('email');

        $this->userService->updateUserEmail($data, $user);

        return redirect()->back()
                         ->withFlashSuccess(__('Profile updated.'));
    }
}

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

    /**
     * @codeCoverageIgnore
     */
    public function dataTable()
    {
        $modelUser = User::query();

        return datatables($modelUser)
            ->editColumn('name', function (User $user) {
                return '<a href="'.route('user.edit', $user->name).'">'.$user->name.'</a>';
            })
            ->editColumn('created_at', function (User $user) {
                return [
                    'display'   => '<span title="'.$user->created_at->toDayDateTimeString().'" data-toggle="tooltip" style="cursor: default;">'.$user->created_at->diffForHumans().'</span>',
                    'timestamp' => $user->created_at->timestamp,
                ];
            })
            ->editColumn('updated_at', function (User $user) {
                return [
                    'display'   => '<span title="'.$user->updated_at->toDayDateTimeString().'" data-toggle="tooltip" style="cursor: default;">'.$user->updated_at->diffForHumans().'</span>',
                    'timestamp' => $user->updated_at->timestamp,
                ];
            })
            ->addColumn('action', function (User $user) {
                return
                    '<div class="btn-group" role="group" aria-label="Basic example">
                        <div class="btn-group" role="group" aria-label="Basic example">
                        <a role="button" class="btn" href="'.route('user.edit', $user->name).'" title="'.__('Details').'" data-toggle="tooltip"><i class="fas fa-user-edit"></i></a>
                        <a role="button" class="btn" href="'.route('user.change-password', $user->name).'" title="'.__('Change Password').'" data-toggle="tooltip"><i class="fas fa-key"></i></a>
                        </div>
                    </div>';
            })
            ->rawColumns(['name', 'created_at.display', 'updated_at.display', 'action'])
            ->toJson();
    }
}

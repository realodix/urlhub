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
     * @param UserService $userSrvc \App\Services\UserService
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
     * @param User $user \App\Models\User
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
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(UpdateUserEmail $request, User $user)
    {
        $this->authorize('update', $user);

        $data = $request->only('email');

        $this->userSrvc->updateUserEmail($data, $user);

        return redirect()->back()
                         ->withFlashSuccess(__('Profile updated.'));
    }

    /**
     * @return string JSON
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
                return '<div class="btn-group" role="group" aria-label="Basic example">
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

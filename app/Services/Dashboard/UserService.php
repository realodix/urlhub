<?php

namespace App\Services\Dashboard;

use App\User;

class UserService
{
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

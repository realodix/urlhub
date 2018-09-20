<?php

namespace App\Http\Controllers\Backend\User;

use App\Http\Controllers\Controller;
use App\User;
use Yajra\Datatables\Datatables;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:admin']);
    }

    public function index()
    {
        return view('backend.user.index');
    }

    public function getData()
    {
        $users = User::query();

        return Datatables::of($users)
                ->editColumn('name', function ($user) {
                    return
                    '<a href="'.route('user.edit', $user->name).'">'.$user->name.'</a>';
                })
                ->editColumn('created_at', function ($user) {
                    return
                    '<span title="'.$user->created_at.'">'.$user->created_at->diffForHumans().'</span>';
                })
                ->addColumn('action', function ($user) {
                    return
                    '<div class="btn-group" role="group" aria-label="Basic example">
                        <div class="btn-group" role="group" aria-label="Basic example">
                          <a role="button" class="btn" href="'.route('user.edit', $user->name).'" title="'.__('Details').'"><i class="fa fa-eye"></i></a>
                          <a role="button" class="btn text-danger" href="'.route('user.change-password', $user->name).'" title="'.__('Change Password').'"><i class="fas fa-key"></i></a>
                        </div>
                     </div>';
                })
                ->rawColumns(['name', 'created_at', 'action'])
                ->toJson();
    }
}

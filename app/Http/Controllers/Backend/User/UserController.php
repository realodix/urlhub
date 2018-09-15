<?php

namespace App\Http\Controllers\Backend\User;

use App\Http\Controllers\Controller;
use App\User;

class UserController extends Controller
{
    public function index()
    {
        return view('backend.user.index', [
            'users' => $users = User::all(),
        ]);
    }
}

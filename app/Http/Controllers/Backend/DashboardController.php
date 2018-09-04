<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $myurls = User::find(Auth::id())->url;
        $sorted = $myurls->sortByDesc('created_at');

        return view('backend.dashboard', [
            'myurls' => $sorted,
        ]);
    }
}

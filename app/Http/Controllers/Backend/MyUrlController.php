<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Url;
use App\User;
use Illuminate\Support\Facades\Auth;

class MyUrlController extends Controller
{
    public function index()
    {
        $myurls = User::find(Auth::id())->url;
        $sorted = $myurls->sortByDesc('created_at');

        return view('backend.my-url', [
            'myurls' => $sorted,
        ]);
    }
}

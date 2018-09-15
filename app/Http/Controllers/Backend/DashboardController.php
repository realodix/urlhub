<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Url;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        return view('backend.dashboard', [
            'myurls'    => Url::where('user_id', Auth::id())->get(),
        ]);
    }

    public function delete($id)
    {
        Url::destroy($id);

        return redirect()->back();
    }
}

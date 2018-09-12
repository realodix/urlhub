<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Url;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $myurls = Url::where('user_id', Auth::id())
                        ->orderBy('updated_at', 'desc')
                        ->paginate(25);

        $total = Url::where('user_id', Auth::id())
                        ->count();

        return view('backend.dashboard', [
            'myurls'    => $myurls,
            'total'     => $total
        ]);
    }
}

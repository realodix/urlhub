<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Url;
use App\User;

class StatisticsController extends Controller
{
    /**
     * StatisticsController constructor.
     */
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    /**
     * Show users all their Short URLs.
     */
    public function view()
    {
        $url = new Url;
        $user = new User;

        return view('backend.statistics', [
            'capacity'             => $url->keywordCapacity(),
            'remaining'            => $url->keywordRemaining(),
            'totalShortUrl'        => $url->totalShortUrl(),
            'totalShortUrlByGuest' => $url->totalShortUrlById(),
            'totalClicks'          => $url->totalClicks(),
            'totalClicksByGuest'   => $url->totalClicksById(),
            'totalUser'            => $user->totalUser(),
            'totalGuest'           => $user->totalGuest(),
        ]);
    }
}

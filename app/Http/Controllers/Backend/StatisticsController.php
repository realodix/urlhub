<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Url;
use App\User;

class StatisticsController extends Controller
{
    /**
     * Show users all their Short URLs.
     */
    public function view()
    {
        $url = new Url;
        $user = new User;

        return view('backend.statistics', [
            'capacity'             => $url->url_key_capacity(),
            'remaining'            => $url->url_key_remaining(),
            'totalShortUrl'        => $url->totalShortUrl(),
            'totalShortUrlByGuest' => $url->totalShortUrlById(),
            'totalClicks'          => $url->totalClicks(),
            'totalClicksByGuest'   => $url->totalClicksById(),
            'totalUser'            => $user->totalUser(),
            'totalGuest'           => $user->totalGuest(),
        ]);
    }
}

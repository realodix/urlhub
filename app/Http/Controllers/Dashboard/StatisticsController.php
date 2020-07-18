<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\KeyService;
use App\Services\UrlService;

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
        $user = new User;
        $urlSrvc = new UrlService;
        $keySrvc = new KeyService;

        return view('backend.statistics', [
            'keyCapacity'          => $keySrvc->keyCapacity(),
            'keyRemaining'         => $keySrvc->keyRemaining(),
            'shortUrlCount'        => $urlSrvc->shortUrlCount(),
            'shortUrlCountByGuest' => $urlSrvc->shortUrlCountOwnedBy(),
            'clickCount'           => $urlSrvc->clickCount(),
            'clickCountFromGuest'  => $urlSrvc->clickCountOwnedBy(),
            'userCount'            => $user->userCount(),
            'guestCount'           => $user->guestCount(),
        ]);
    }
}

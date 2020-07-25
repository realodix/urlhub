<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\KeyService;
use App\Services\UrlService;
use App\Services\UserService;

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
        $userSrvc = new UserService;
        $urlSrvc = new UrlService;
        $keySrvc = new KeyService;

        return view('backend.statistics', [
            'keyCapacity'          => $keySrvc->keyCapacity(),
            'keyRemaining'         => $keySrvc->keyRemaining(),
            'remainingPercentage'  => remainingPercentage($keySrvc->numberOfUsedKey(), $keySrvc->keyCapacity()),
            'shortUrlCount'        => $urlSrvc->shortUrlCount(),
            'shortUrlCountByGuest' => $urlSrvc->shortUrlCountOwnedBy(),
            'clickCount'           => $urlSrvc->clickCount(),
            'clickCountFromGuest'  => $urlSrvc->clickCountOwnedBy(),
            'userCount'            => $userSrvc->userCount(),
            'guestCount'           => $userSrvc->guestCount(),
        ]);
    }
}

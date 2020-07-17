<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Url;
use App\Models\User;
use App\Services\KeyService;

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
        $keySrvc = new KeyService();

        return view('backend.statistics', [
            'keyCapacity'          => $keySrvc->keyCapacity(),
            'keyRemaining'         => $keySrvc->keyRemaining(),
            'shortUrlCount'        => $url->shortUrlCount(),
            'shortUrlCountByGuest' => $url->shortUrlCountOwnedBy(),
            'clickCount'           => $url->clickCount(),
            'clickCountFromGuest'  => $url->clickCountOwnedBy(),
            'userCount'            => $user->userCount(),
            'guestCount'           => $user->guestCount(),
        ]);
    }
}

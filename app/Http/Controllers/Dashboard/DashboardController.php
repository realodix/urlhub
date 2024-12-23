<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Url;
use App\Models\Visit;

class DashboardController extends Controller
{
    /**
     * Show all user short URLs.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function view()
    {
        $urlVisitCount = app(Visit::class)->currentUserLinkVisitCount();

        return view('backend.dashboard', [
            'url' => app(Url::class),
            'urlVisitCount' => n_abb($urlVisitCount),
        ]);
    }
}

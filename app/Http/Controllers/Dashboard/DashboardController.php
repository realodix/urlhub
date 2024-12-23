<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Url;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Routing\Controllers\{HasMiddleware, Middleware};

class DashboardController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [new Middleware('role:admin', except: ['view'])];
    }

    /**
     * Show the dashboard and the URL list.
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

    /**
     * Show all short URLs created by all users.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function allUrlView()
    {
        return view('backend.url-list');
    }

    /**
     * Show all short links from specific user.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function userLinkView(string $author)
    {
        return view('backend.url-list-of-user', [
            'authorName' => $author,
            'authorId'   => User::where('name', $author)->first()->id,
        ]);
    }

    /**
     * Show all short URLs created by guest.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function guestLinkView()
    {
        return view('backend.url-list-of-guest');
    }
}

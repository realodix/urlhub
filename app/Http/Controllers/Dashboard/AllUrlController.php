<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Routing\Controllers\{HasMiddleware, Middleware};

class AllUrlController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [new Middleware('role:admin')];
    }

    /**
     * Show all short URLs created by all users.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function view()
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
            'authorId' => User::where('name', $author)->first()->id,
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

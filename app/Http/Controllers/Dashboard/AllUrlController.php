<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Url;
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
     * Show all short URLs created by guest.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function guestLinkView()
    {
        return view('backend.url-list-of-guest');
    }

    /**
     * Delete a Short URL on user (Admin) request.
     *
     * @param Url $url \App\Models\Url
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Url $url)
    {
        $url->delete();

        return redirect()->back()
            ->withFlashSuccess(__('Link was successfully deleted.'));
    }
}

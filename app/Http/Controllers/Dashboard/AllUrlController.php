<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Url;
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
     * Show all short URLs created by all users.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function viewFromGuest()
    {
        return view('backend.url-list-of-guest');
    }

    /**
     * Show all short links from specific user.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function userLinkView(string $author)
    {
        $authorId = User::where('name', $author)->first()->id;

        return view('backend.user-link', [
            'authorName' => $author,
            'authorId' => $authorId
        ]);
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

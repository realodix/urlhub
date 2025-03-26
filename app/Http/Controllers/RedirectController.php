<?php

namespace App\Http\Controllers;

use App\Models\Url;
use App\Services\RedirectService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RedirectController extends Controller
{
    /**
     * Redirect the client to the intended long URL (no checks are performed)
     * and executes the create visitor data task.
     *
     * @param Url $url \App\Models\Url
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Url $url)
    {
        // If the link has a password, redirect to the password form
        if ($url->password) {
            return to_route('link.password', $url->keyword);
        }

        // If the link is expired, redirect to the expired page
        if ($url->isExpired()) {
            if ($url->expired_url) {
                return redirect()->away($url->expired_url);
            }

            return to_route('link.expired', $url);
        }

        return app(RedirectService::class)->execute($url);
    }

    /**
     * Displays the password form for a link.
     *
     * @param Url $url \App\Models\Url
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function password(Url $url)
    {
        if (!$url->password) {
            return to_route('link_detail', $url->keyword);
        }

        return view('frontend.linkpassword', ['url' => $url]);
    }

    /**
     * Validate the given password against the stored one for the given URL.
     * If it matches, redirect the user to the long URL.
     * If it doesn't, redirect the user back with an error message.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function validatePassword(Request $request, Url $url)
    {
        if (Hash::check($request->password, $url->password)) {
            return app(RedirectService::class)->execute($url);
        }

        return back()->withErrors(['password' => 'The password is incorrect.']);
    }
}

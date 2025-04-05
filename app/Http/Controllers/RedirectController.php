<?php

namespace App\Http\Controllers;

use App\Actions\RedirectToDestination;
use App\Models\Url;

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

        return app(RedirectToDestination::class)->handle($url);
    }
}

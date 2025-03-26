<?php

namespace App\Http\Controllers;

use App\Models\Url;
use App\Services\RedirectService;

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
}

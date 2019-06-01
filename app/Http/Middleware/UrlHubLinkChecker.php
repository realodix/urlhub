<?php

namespace App\Http\Middleware;

use App\Services\UrlService;
use App\Url;
use Closure;
use Illuminate\Support\Facades\Auth;

class UrlHubLinkChecker
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $UrlSrvc = new UrlService();
        $long_url = rtrim($request->long_url, '/');

        //
        // If url_key is not available, prevent creating short URLs.
        //
        if ($UrlSrvc->url_key_remaining() == 0) {
            return redirect()->back()
                             ->withFlashError(__('Sorry, our service is currently under maintenance.'));
        }

        //
        // Check whether the URL entered is already in database.
        // If there is already, show a warning.
        //
        if (Auth::check()) {
            $s_url = Url::whereUserId(Auth::id())
                          ->whereLongUrl($long_url)
                          ->first();
        } else {
            $s_url = Url::whereLongUrl($long_url)
                          ->whereNull('user_id')
                          ->first();
        }

        if ($s_url) {
            return redirect()->route('short_url.stats', $s_url->url_key)
                             ->with('msgLinkAlreadyExists', __('Link already exists.'));
        }

        return $next($request);
    }
}

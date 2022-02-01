<?php

namespace App\Http\Middleware;

use App\Models\Url;
use App\Services\KeyService;
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
        $keySrvc = new KeyService();
        $longUrl = rtrim($request->long_url, '/');

        /*
        |----------------------------------------------------------------------
        | Key Remaining
        |----------------------------------------------------------------------
        |
        | Prevent create short URLs when the Random Key Generator reaches the
        | maximum limit and cannot generate more keys.
        |
        */

        if ($keySrvc->keyRemaining() == 0) {
            return redirect()
                   ->back()
                   ->withFlashError(
                       __('Sorry, our service is currently under maintenance.')
                   );
        }

        /*
        |----------------------------------------------------------------------
        | Long Url Exists
        |----------------------------------------------------------------------
        |
        | Check if a long URL already exists in the database. If found, display
        | a warning.
        |
        */

        if (Auth::check()) {
            $s_url = Url::whereUserId(Auth::id())
                          ->whereLongUrl($longUrl)
                          ->first();
        } else {
            $s_url = Url::whereLongUrl($longUrl)
                          ->whereNull('user_id')
                          ->first();
        }

        if ($s_url) {
            return redirect()->route('short_url.stats', $s_url->keyword)
                             ->with('msgLinkAlreadyExists', __('Link already exists.'));
        }

        return $next($request);
    }
}

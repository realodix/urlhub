<?php

namespace App\Http\Middleware;

use App\Url;
use Closure;
use Facades\App\Helpers\UrlHlp;
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
        $long_url = rtrim($request->long_url, '/');

        if (UrlHlp::url_key_remaining() == 0) {
            return redirect()->back()
                             ->withFlashError(__('Sorry, our service is currently under maintenance.'));
        }

        //
        // Checks whether the url entered is already in the database.
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

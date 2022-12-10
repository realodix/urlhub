<?php

namespace App\Http\Middleware;

use App\Models\Url;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;

class UrlHubLinkChecker
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        $url = new Url;
        $longUrl = rtrim($request->long_url, '/');

        if (! $this->cutomKeywordIsValid($request)) {
            return redirect()->back()
                ->withFlashError(__('Custom keyword not available.'));
        }

        /*
        |----------------------------------------------------------------------
        | Key Remaining
        |----------------------------------------------------------------------
        |
        | Prevent create short URLs when the Random Key Generator reaches the
        | maximum limit and cannot generate more keys.
        |
        */

        if ($url->keyRemaining() === 0) {
            return redirect()->back()
                ->withFlashError(__('Sorry, our service is currently under maintenance.'));
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
            return redirect()->route('su_stat', $s_url->keyword)
                    ->with('msgLinkAlreadyExists', __('Link already exists.'));
        }

        return $next($request);
    }

    /**
     * Check if custom keyword is valid.
     * - Prevent registered routes from being used as custom keywords.
     * - Prevent using blacklisted words or reserved keywords as custom keywords.
     *
     * @param \Illuminate\Http\Request $request
     */
    private function cutomKeywordIsValid($request): bool
    {
        $value = $request->custom_key;
        $routes = array_map(
            fn (Route $route) => $route->uri,
            \Route::getRoutes()->get()
        );

        if (in_array($value, $routes) || in_array($value, config('urlhub.reserved_keyword'))) {
            return false;
        }

        return true;
    }
}

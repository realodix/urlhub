<?php

namespace App\Http\Middleware;

use App\Url;
use Closure;
use Facades\App\Helpers\UrlHlp;
use Illuminate\Support\Facades\Auth;

class LinkChecker
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
        $long_url = $request->long_url;

        // check whether the domain is blacklisted
        $domains_blocked = UrlHlp::url_parsed(config('plur.domains_blocked'));

        foreach ($domains_blocked as $domain_blocked) {
            $url_segment = ('://'.$domain_blocked.'/');

            if (strstr($long_url, $url_segment)) {
                return redirect()->back()
                                 ->with('error', __('Sorry, the URL you entered is on our internal blacklist. It may have been used abusively in the past, or it may link to another URL redirection service.'));
            }
        }

        // check whether it is already in the database
        $s_url = Url::where('long_url', $long_url)
                    ->where('user_id', '==', 0)
                    ->first();

        if (Auth::check()) {
            $s_url = Url::where('long_url', $long_url)
                        ->where('user_id', Auth::id())
                        ->first();
        }

        if ($s_url) {
            return redirect('/+'.$s_url->short_url)->with('msgLinkAlreadyExists', __('Link already exists'));
        }

        return $next($request);
    }
}

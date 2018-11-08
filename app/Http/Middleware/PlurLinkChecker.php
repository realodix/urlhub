<?php

namespace App\Http\Middleware;

use App\Url;
use Closure;
use Facades\App\Helpers\UrlHlp;
use Illuminate\Support\Facades\Auth;

class PlurLinkChecker
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

        if (UrlHlp::url_key_remaining() == 0) {
            return redirect()->back()
                             ->with('error', __('Sorry, our service is currently under maintenance.'));
        }

        // check whether the domain is blacklisted
        $domains_blocked = remove_url_schemes(config('plur.domains_blocked'));

        foreach ($domains_blocked as $domain_blocked) {
            $url_segment = ('://'.$domain_blocked.'/');
            $url_segment2 = ('://www.'.$domain_blocked.'/');

            if (strstr($long_url, $url_segment) || strstr($long_url, $url_segment2)) {
                return redirect()->back()
                                 ->with('error', __('Sorry, the URL you entered is on our internal blacklist. It may have been used abusively in the past, or it may link to another URL redirection service.'));
            }
        }

        // check whether it is already in the database
        $s_url = Url::where('long_url', $long_url)
                    ->whereNull('user_id')
                    ->first();

        if (Auth::check()) {
            $s_url = Url::where('long_url', $long_url)
                        ->where('user_id', Auth::id())
                        ->first();
        }

        if ($s_url) {
            return redirect('/+'.$s_url->url_key)->with('msgLinkAlreadyExists', __('Link already exists.'));
        }

        return $next($request);
    }
}

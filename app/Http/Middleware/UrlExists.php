<?php

namespace App\Http\Middleware;

use App\Url;
use Closure;

class UrlExists
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

        $s_url = Url::where('long_url', $long_url)->first();
        if ($s_url) {
            return redirect('/+'.$s_url->short_url)->with('msgLinkAlreadyExists', 'Link already exists');
        }

        return $next($request);
    }
}

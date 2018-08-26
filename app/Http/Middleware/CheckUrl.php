<?php

namespace App\Http\Middleware;

use App\Url;
use Closure;
use Facades\App\Helpers\UrlHlp;

class CheckUrl
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
        $long_url = UrlHlp::urlToDomain($request->long_url);

        $s_url = Url::where('long_url', $long_url)->first();
        if ($s_url) {
            return redirect('/+'.$s_url->short_url)->with('msgLinkAlreadyExists', 'Link already exists');
        }

        $s_url_cst = Url::where('short_url_custom', $request->short_url_custom)->first();
        if ($s_url_cst) {
            return back()->with('cst_exist', 'Sorry, the custom url you entered is not available.')
                         ->with(['long_url' => $request->long_url]);
        }

        return $next($request);
    }
}

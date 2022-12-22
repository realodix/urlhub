<?php

namespace App\Actions;

use App\Helpers\Helper;
use App\Models\Url;
use App\Models\Visit;

class UrlRedirectAction
{
    /**
     * Handle the HTTP redirect and return the redirect response.
     *
     * Redirect client to an existing short URL (no check performed) and
     * execute tasks update clicks for short URL.
     *
     * @param Url $url \App\Models\Url
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleHttpRedirect(Url $url)
    {
        $url->increment('click');
        $this->storeVisitStat($url);

        $statusCode = (int) config('urlhub.redirect_status_code');
        $maxAge = (int) config('urlhub.redirect_cache_max_age');
        $headers = [
            'Cache-Control' => sprintf('private,max-age=%s', $maxAge),
        ];

        return redirect()->away($url->destination, $statusCode, $headers);
    }

    /**
     * Create visit statistics and store it in the database.
     *
     * @param Url $url \App\Models\Url
     * @return void
     */
    private function storeVisitStat(Url $url)
    {
        Visit::create([
            'url_id'  => $url->id,
            'referer' => request()->headers->get('referer'),
            'ip'      => Helper::anonymizeIp(request()->ip()),
        ]);
    }
}

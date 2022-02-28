<?php

namespace App\Services;

use App\Models\Url;
use App\Models\Visit;
use Illuminate\Http\RedirectResponse;

class UrlRedirectionService
{
    /**
     * Handle the HTTP redirect and return the redirect response.
     *
     * Redirect client to an existing short URL (no check performed) and
     * execute tasks update clicks for short URL.
     *
     * @param  Url  $url  \App\Models\Url
     * @return RedirectResponse
     */
    public function handleHttpRedirect(Url $url)
    {
        $url->increment('clicks');
        $this->storeVisitStat($url);

        $headers = [
            'Cache-Control' => sprintf('private,max-age=%s', uHub('redirect_cache_lifetime')),
        ];

        return redirect()->away($url->long_url, uHub('redirect_status_code'), $headers);
    }

    /**
     * Create visit statistics and store it in the database.
     *
     * @param  Url  $url  \App\Models\Url
     */
    private function storeVisitStat(Url $url)
    {
        Visit::create([
            'url_id'  => $url->id,
            'referer' => request()->headers->get('referer'),
            'ip'      => $url->anonymizeIp(request()->ip()),
        ]);
    }
}

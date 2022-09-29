<?php

namespace App\Services;

use App\Models\{Url, Visit};

class UrlRedirectionService
{
    /**
     * Handle the HTTP redirect and return the redirect response.
     *
     * Redirect client to an existing short URL (no check performed) and
     * execute tasks update clicks for short URL.
     *
     * @param  Url                               $url \App\Models\Url
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleHttpRedirect(Url $url)
    {
        $url->increment('clicks');
        $this->storeVisitStat($url);

        $headers = [
            'Cache-Control' => sprintf('private,max-age=%s', (int) config('urlhub.redirect_cache_lifetime')),
        ];

        return redirect()->away($url->long_url, (int) config('urlhub.redirect_status_code'), $headers);
    }

    /**
     * Create visit statistics and store it in the database.
     *
     * @param  Url  $url \App\Models\Url
     * @return void
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

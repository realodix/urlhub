<?php

namespace App\Services;

use App\Models\Url;

class UrlRedirection
{
    /**
     * Execute the HTTP redirect and return the redirect response.
     *
     * @param Url $url \App\Models\Url
     * @return \Illuminate\Http\RedirectResponse
     */
    public function execute(Url $url)
    {
        $statusCode = config('urlhub.redirect_status_code');
        $maxAge = config('urlhub.redirect_cache_max_age');
        $headers = ['Cache-Control' => sprintf('private,max-age=%s', $maxAge)];

        return redirect()->away($url->destination, $statusCode, $headers);
    }
}

<?php

namespace App\Services;

use App\Models\Url;
use Illuminate\Http\RedirectResponse;

class UrlRedirection
{
    /**
     * Execute the HTTP redirect and return the redirect response.
     *
     * @param Url $url \App\Models\Url
     */
    public function execute(Url $url): RedirectResponse
    {
        $statusCode = (int) config('urlhub.redirect_status_code');
        $maxAge = (int) config('urlhub.redirect_cache_max_age');
        $headers = [
            'Cache-Control' => sprintf('private,max-age=%s', $maxAge),
        ];

        return redirect()->away($url->destination, $statusCode, $headers);
    }
}

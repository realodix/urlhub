<?php

namespace App\Services;

use Illuminate\Support\Uri;

class RedirectService
{
    /**
     * Builds a URL by merging query parameters from the current request with
     * those in the provided base URL. The parameter in the short link will
     * override its counterpart in the base URL in case of duplicates.
     *
     * @param string $baseUrl The base URL to which query parameters will be
     *                        appended or merged.
     * @param array $currentQuery Query parameters from the current request.
     * @return string
     */
    public function buildWithQuery($baseUrl, $currentQuery)
    {
        return Uri::of($baseUrl)->withQuery($currentQuery)->value();
    }
}

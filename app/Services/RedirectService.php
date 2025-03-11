<?php

namespace App\Services;

use App\Models\Url;
use App\Settings\GeneralSettings;
use Illuminate\Support\Uri;

class RedirectService
{
    /**
     * Execute the HTTP redirect and return the redirect response.
     *
     * @param Url $url \App\Models\Url
     * @return \Illuminate\Http\RedirectResponse
     */
    public function execute(Url $url)
    {
        $settings = app(GeneralSettings::class);
        $statusCode = config('urlhub.redirection_status_code');

        $maxAge = $settings->redirect_cache_max_age;
        $headers = ['Cache-Control' => sprintf('private,max-age=%s', $maxAge)];
        if ($maxAge === 0) {
            $headers = ['Cache-Control' => 'max-age=0, must-revalidate'];
        }

        $destinationUrl = $url->destination;

        /** @var array $currentQuery */
        $currentQuery = request()->query(); // The `$key` parameter is not filled, so it will return an `array`.
        if (! empty($currentQuery)
            && $settings->forward_query === true // The `forward_query` setting is enabled on global level
            && $url->author->forward_query === true // The `forward_query` setting is enabled on author level
            && $url->forward_query === true // The `forward_query` setting is enabled on URL item level
        ) {
            $destinationUrl = $this->resolveQuery($url->destination, $currentQuery);
        }

        return redirect()->away($destinationUrl, $statusCode, $headers);
    }

    /**
     * Resolves a URL by merging query parameters from the current request with
     * those in the provided base URL. The parameter in the short link will
     * override its counterpart in the destination URL in case of duplicates.
     *
     * @param string $baseUrl The base URL to which query parameters will be
     *                        appended or merged.
     * @param array $currentQuery Query parameters from the current request.
     * @return string
     */
    public function resolveQuery($baseUrl, $currentQuery)
    {
        return Uri::of($baseUrl)->withQuery($currentQuery)->__toString();
    }
}

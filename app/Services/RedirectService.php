<?php

namespace App\Services;

use App\Helpers\Helper;
use App\Models\Url;
use App\Settings\GeneralSettings;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Uri;

class RedirectService
{
    /**
     * Handles the redirect logic and visitor creation.
     *
     * @param Url $url \App\Models\Url
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function execute(Url $url)
    {
        return DB::transaction(function () use ($url) {
            app(VisitorService::class)->create($url);

            return $this->handleRedirect($url);
        });
    }

    /**
     * Execute the HTTP redirect and return the redirect response.
     *
     * @param Url $url \App\Models\Url
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleRedirect(Url $url)
    {
        $settings = app(GeneralSettings::class);
        $statusCode = config('urlhub.redirection_status_code');
        $maxAge = $settings->redirect_cache_max_age;
        $destinationUrl = $this->resolveTargetLink($url);

        $response = redirect()->away($destinationUrl, $statusCode);
        $response->setMaxAge($maxAge);

        if ($maxAge < 1) {
            $response->headers->addCacheControlDirective('must-revalidate');
        }

        return $response;
    }

    /**
     * Resolve the target link for the redirect.
     *
     * @param Url $url \App\Models\Url
     */
    public function resolveTargetLink(Url $url): string
    {
        $device = Helper::deviceDetector();
        $destinationUrl = $url->destination;

        if (!empty($url->dest_android) && $device->getOs('family') === 'Android') {
            $destinationUrl = $url->dest_android;
        } elseif (!empty($url->dest_ios) && $device->getOs('family') === 'iOS') {
            $destinationUrl = $url->dest_ios;
        }

        /** @var array $currentQuery */
        $currentQuery = request()->query(); // Array, because `$key` parameter is not filled
        if ($this->canForwardQuery($url, $currentQuery)) {
            $destinationUrl = $this->buildWithQuery($destinationUrl, $currentQuery);
        }

        return $destinationUrl;
    }

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

    /**
     * Determines whether query parameters should be forwarded to the destination
     * URL.
     *
     * @param Url $url The URL model.
     * @param array $currentQuery The current query parameters.
     * @return bool True if the query should be forwarded, false otherwise.
     */
    private function canForwardQuery(Url $url, array $currentQuery): bool
    {
        $settings = app(GeneralSettings::class);

        return !empty($currentQuery) // Query parameters are present
            && $settings->forward_query === true  // Enabled on global level
            && $url->author->forward_query === true // Enabled on author level
            && $url->forward_query === true;       // Enabled on URL item level
    }
}

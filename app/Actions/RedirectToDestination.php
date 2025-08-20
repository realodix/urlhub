<?php

namespace App\Actions;

use App\Helpers\Helper;
use App\Models\Url;
use App\Settings\GeneralSettings;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Uri;

class RedirectToDestination
{
    /**
     * Handles the redirection of a short URL request to its destination and
     * records the visit.
     *
     * @param Url $url \App\Models\Url
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function handle(Url $url)
    {
        return DB::transaction(function () use ($url) {
            app(RecordVisit::class)->handle($url);

            $statusCode = config('urlhub.redirection_status_code');
            $maxAge = app(GeneralSettings::class)->redirect_cache_max_age;
            $destinationUrl = $this->resolveTargetLink($url);

            $response = redirect()->away($destinationUrl, $statusCode);
            $response->setMaxAge($maxAge);

            if ($maxAge < 1) {
                $response->headers->addCacheControlDirective('must-revalidate');
            }

            return $response;
        });
    }

    /**
     * Resolve the target link for the redirect.
     *
     * This method checks the device-specific destination URL first. If no
     * device-specific URL is set or matches, it defaults to the main
     * destination. Additionally, it appends current request query
     * parameters if allowed by settings.
     *
     * @param Url $url \App\Models\Url
     */
    private function resolveTargetLink(Url $url): string
    {
        $device = Helper::deviceDetector();
        $destinationUrl = $url->destination;

        if (!empty($url->dest_android) && $device->getOs('family') == 'Android') {
            $destinationUrl = $url->dest_android;
        } elseif (!empty($url->dest_ios) && $device->getOs('family') == 'iOS') {
            $destinationUrl = $url->dest_ios;
        }

        /** @var array $currentQuery */
        $currentQuery = request()->query(); // Array, because `$key` parameter is not filled
        if ($this->canForwardQuery($url, $currentQuery)) {
            $destinationUrl = Uri::of($destinationUrl)->withQuery($currentQuery)->value();
        }

        return $destinationUrl;
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
            && $settings->forward_query === true // Enabled on global level
            && $url->author->forward_query === true // Enabled on account level
            && $url->forward_query === true;       // Enabled on URL item level
    }
}

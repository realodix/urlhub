<?php

namespace App\Services;

use App\Helpers\Helper;
use App\Models\Url;
use App\Models\User;
use App\Models\Visit;
use Spatie\Url\Url as SpatieUrl;

class VisitorService
{
    public function __construct(
        public User $user,
    ) {
    }

    /**
     * Store the visitor data.
     *
     * @param Url $url \App\Models\Url
     * @return void
     */
    public function create(Url $url)
    {
        $logBotVisit = config('urlhub.track_bot_visits');
        $dd = Helper::deviceDetector();
        // Gunakan use statement
        $referer = request()->header('referer');

        if ($logBotVisit === false && $dd->isBot() === true) {
            return;
        }

        Visit::create([
            'url_id'         => $url->id,
            'visitor_id'     => $this->user->signature(),
            'is_first_click' => $this->isFirstClick($url),
            'referer'        => $this->getRefererHost($referer),
        ]);
    }

    /**
     * Check if the visitor has clicked the link before. If the visitor has not
     * clicked the link before, return true.
     *
     * @param Url $url \App\Models\Url
     */
    public function isFirstClick(Url $url): bool
    {
        $hasVisited = $url->visits()
            ->whereVisitorId($this->user->signature())
            ->exists();

        return $hasVisited ? false : true;
    }

    /**
     * Get the referer host.
     *
     * Only input the URL host into the referer column
     */
    public function getRefererHost(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $referer = SpatieUrl::fromString($value);
        $path = $referer->getPath();

        return str_replace($path, '', $value);
    }
}

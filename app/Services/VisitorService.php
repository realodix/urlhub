<?php

namespace App\Services;

use App\Helpers\Helper;
use App\Models\Url;
use App\Models\Visit;
use App\Settings\GeneralSettings;
use Illuminate\Support\Uri;

class VisitorService
{
    public function __construct(
        protected UserService $userService,
        protected GeneralSettings $settings,
    ) {}

    /**
     * Store the visitor data.
     *
     * @param Url $url \App\Models\Url
     * @return void
     */
    public function create(Url $url)
    {
        $logBotVisit = $this->settings->track_bot_visits;
        $device = Helper::deviceDetector();
        $referer = request()->header('referer');

        if ($logBotVisit === false && $device->isBot() === true) {
            return;
        }

        Visit::create([
            'url_id'         => $url->id,
            'user_type'      => $this->userService->userType(),
            'user_uid'       => $this->userService->signature(),
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
            ->where('user_uid', $this->userService->signature())
            ->exists();

        return $hasVisited ? false : true;
    }

    /**
     * Get the referer host
     *
     * Only input the URL host into the referer column.
     */
    public function getRefererHost(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $referer = Uri::of($value);

        return $referer->scheme().'://'.$referer->host();
    }
}
